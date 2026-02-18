<?php
// https://app.metricool.com/api/v2/scheduler/library/posts?userId=4505229&blogId=5820853
class MetricoolPoster {
    private $userToken = 'YUYFRBPBVSRLFWGKQYUKBVTLBDFDAQXYOJXFNCJUZHRYMCSPRTYSTQWTKPZSLJFS';
    private $userId = '4505229';
    private $baseUrl = 'https://app.metricool.com/api';

    // 1. Obtener los IDs de tus marcas
    public function getBrands() {
        return $this->makeRequest('/v2/settings/brands', 'GET');
    }

    private function makeRequest($endpoint, $method, $data = []) {
        $curl = curl_init();
        $headers = [
            "X-Mc-Auth: " . $this->userToken,
            "Content-Type: application/json"
        ];

        $url = $this->baseUrl . $endpoint;
        
        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ];

        if (($method === 'POST' || $method === 'PUT') && !empty($data)) {
            $opts[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);

        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            throw new Exception("cURL Error: " . $curlError);
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMsg = "API Error (HTTP $httpCode): ";
            if (isset($decoded['detail'])) {
                $errorMsg .= $decoded['detail'];
            } else if (isset($decoded['title'])) {
                $errorMsg .= $decoded['title'];
            } else {
                $errorMsg .= $response;
            }
            throw new Exception($errorMsg);
        }
        
        return $decoded;
    }

    /**
     * Subir imagen a S3 via Metricool API
     * @param string $imageUrl URL pública de la imagen
     * @return string|null S3 key para usar en el post
     */
    public function uploadMedia($imageUrl) {
        // 1. Descargar la imagen desde la URL
        $imageData = file_get_contents($imageUrl);
        if ($imageData === false) {
            throw new Exception("No se pudo descargar la imagen: $imageUrl");
        }
        
        $imageSize = strlen($imageData);
        
        // Detectar tipo de contenido
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        $contentType = $contentTypes[strtolower($extension)] ?? 'image/jpeg';
        
        // 2. Crear transacción de upload en S3
        $uploadRequest = [
            'contentType' => $contentType,
            'size' => $imageSize,
            'fileExtension' => $extension
        ];
        
        $uploadResponse = $this->makeRequest('/v2/media/s3/upload-transactions', 'PUT', $uploadRequest);
        
        if (!isset($uploadResponse['data']['presignedUrl'])) {
            throw new Exception("No se recibió URL presignada de S3");
        }
        
        $presignedUrl = $uploadResponse['data']['presignedUrl'];
        $s3Key = $uploadResponse['data']['key'];
        
        // 3. Subir imagen directamente a S3 usando la URL presignada
        $ch = curl_init($presignedUrl);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $imageData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: $contentType",
                "Content-Length: $imageSize"
            ]
        ]);
        
        $s3Response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 300) {
            throw new Exception("Error subiendo a S3 (HTTP $httpCode): $s3Response");
        }
        
        echo "✓ Imagen subida: $s3Key\n";
        return $s3Key;
    }

    // Lógica para componer el texto final
    // $mediaUrls puede ser URLs directas de imágenes
    public function createPost($blogId, $titulo, $cuerpo, $hashtags, $mediaUrls, $dateTime) {
        
        // CONSTRUCCIÓN DEL TEXTO: Unimos las 3 partes con saltos de línea
        $finalText = $titulo . "\n\n" . $cuerpo . "\n\n" . $hashtags;

        // Media: array de URLs
        $mediaArray = [];
        foreach ($mediaUrls as $url) {
            if ($url) $mediaArray[] = $url;
        }

        // Providers debe ser un array de objetos ProviderStatus con propiedad 'network'
        $providers = [
            ['network' => 'facebook'],
            ['network' => 'instagram'],
            ['network' => 'linkedin'],
            ['network' => 'Gmb']
        ];

        $postData = [
            'targetBrandId' => $blogId, 
            'text' => $finalText, 
            'publicationDate' => ['dateTime' => $dateTime],
            'draft' => false,
            'media' => $mediaArray,
            'saveExternalMediaFiles' => true, // Permite usar URLs externas
            'providers' => $providers
        ];

        return $this->makeRequest('/v2/scheduler/posts?blogId=' . $blogId, 'POST', $postData);
    }

    /**
     * Crear Video en YouTube (Estándar)
     */
    public function createYoutubeVideo($blogId, $titulo, $descripcion, $videoUrl, $dateTime) {
        $postData = [
            'targetBrandId' => $blogId,
            'text' => $descripcion,
            'publicationDate' => ['dateTime' => $dateTime],
            'draft' => false,
            'media' => [$videoUrl],
            'saveExternalMediaFiles' => true,
            'providers' => [['network' => 'youtube']],
            'youtubeData' => [
                'title' => $titulo,
                'type' => 'VIDEO',
                'privacy' => 'public'
            ]
        ];
        return $this->makeRequest('/v2/scheduler/posts?blogId=' . $blogId, 'POST', $postData);
    }

    /**
     * Crear YouTube Short
     */
    public function createYoutubeShorts($blogId, $titulo, $descripcion, $videoUrl, $dateTime) {
        $postData = [
            'targetBrandId' => $blogId,
            'text' => $descripcion,
            'publicationDate' => ['dateTime' => $dateTime],
            'draft' => false,
            'media' => [$videoUrl],
            'saveExternalMediaFiles' => true,
            'providers' => [['network' => 'youtube']],
            'youtubeData' => [
                'title' => $titulo,
                'type' => 'SHORT',
                'privacy' => 'public'
            ]
        ];
        return $this->makeRequest('/v2/scheduler/posts?blogId=' . $blogId, 'POST', $postData);
    }

    /**
     * Crear Post en TikTok
     */
    public function createTikTokPost($blogId, $text, $videoUrl, $dateTime) {
        $postData = [
            'targetBrandId' => $blogId,
            'text' => $text,
            'publicationDate' => ['dateTime' => $dateTime],
            'draft' => false,
            'media' => [$videoUrl],
            'saveExternalMediaFiles' => true,
            'providers' => [['network' => 'tiktok']],
            'tiktokData' => [
                'privacyOption' => 'PUBLIC'
            ]
        ];
        return $this->makeRequest('/v2/scheduler/posts?blogId=' . $blogId, 'POST', $postData);
    }

    /**
     * Crear Instagram Reel
     */
    public function createInstagramReel($blogId, $text, $videoUrl, $dateTime) {
        $postData = [
            'targetBrandId' => $blogId,
            'text' => $text,
            'publicationDate' => ['dateTime' => $dateTime],
            'draft' => false,
            'media' => [$videoUrl],
            'saveExternalMediaFiles' => true,
            'providers' => [['network' => 'instagram']],
            'instagramData' => [
                'type' => 'REEL',
                'autoPublish' => true,
                'showReelOnFeed' => true
            ]
        ];
        return $this->makeRequest('/v2/scheduler/posts?blogId=' . $blogId, 'POST', $postData);
    }
}


