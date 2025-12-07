<?php
http_response_code(200);
echo json_encode(["status" => "ok", "message" => "Health check passed! Apache is working."]);
