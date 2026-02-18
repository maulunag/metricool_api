#!/bin/bash

# Base URL - assuming localhost for now, user might need to adjust
API_URL="http://localhost:8000/index.php"

echo "Using API URL: $API_URL"

# 1. Test Post General
echo "\n--- Testing Post General ---"
curl -X POST -H "Content-Type: application/json" -d '{
    "action": "post_general",
    "titulo": "Test Title",
    "texto": "Test Body Content",
    "hashtags": "#test",
    "fotos": ["https://example.com/image.jpg"]
}' "$API_URL"

# 2. Test YouTube Video
echo "\n\n--- Testing YouTube Video ---"
curl -X POST -H "Content-Type: application/json" -d '{
    "action": "youtube_video",
    "titulo": "YT Video Title",
    "descripcion": "YT Description",
    "videoUrl": "https://example.com/video.mp4"
}' "$API_URL"

# 3. Test Invalid Action
echo "\n\n--- Testing Invalid Action ---"
curl -X POST -H "Content-Type: application/json" -d '{
    "action": "invalid_action"
}' "$API_URL"
