<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/setSession') !== false) {

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // $token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGFpbXMiOiJwcm9kdWN0czpyZWFkLHByb2R1Y3RzOnVwZGF0ZSxwcm9kdWN0czpkZWxldGUsY2xpZW50czpjcmVhdGUsY2xpZW50czpyZWFkLGNsaWVudHM6dXBkYXRlLGNsaWVudHM6ZGVsZXRlLG9yZGVyczpjcmVhdGUsb3JkZXJzOnJlYWQsb3JkZXJzOnVwZGF0ZSxvcmRlcnM6ZGVsZXRlLGVtcGxveWVlczpjcmVhdGUsZW1wbG95ZWVzOnJlYWQsZW1wbG95ZWVzOnVwZGF0ZSxlbXBsb3llZXM6ZGVsZXRlLGRlcGFydG1lbnRzOmNyZWF0ZSxkZXBhcnRtZW50czpyZWFkLGRlcGFydG1lbnRzOnVwZGF0ZSxkZXBhcnRtZW50czpkZWxldGUscm9sZXM6Y3JlYXRlLHJvbGVzOnJlYWQscm9sZXM6dXBkYXRlLHJvbGVzOmRlbGV0ZSxpdGVtczpjcmVhdGUsaXRlbXM6cmVhZCxpdGVtczp1cGRhdGUsaXRlbXM6ZGVsZXRlLGJ1ZGdldHM6Y3JlYXRlLGJ1ZGdldHM6cmVhZCxidWRnZXRzOnVwZGF0ZSxidWRnZXRzOmRlbGV0ZSxkZWxpdmVyaWVzOmNyZWF0ZSxkZWxpdmVyaWVzOnJlYWQsZGVsaXZlcmllczp1cGRhdGUsZGVsaXZlcmllczpkZWxldGUscHJvZHVjdF90ZW1wbGF0ZXM6Y3JlYXRlLHByb2R1Y3RfdGVtcGxhdGVzOnJlYWQscHJvZHVjdF90ZW1wbGF0ZXM6dXBkYXRlLHByb2R1Y3RfdGVtcGxhdGVzOmRlbGV0ZSxwcm9kdWN0X3RlbXBsYXRlX2l0ZW1zOmNyZWF0ZSxwcm9kdWN0X3RlbXBsYXRlX2l0ZW1zOnJlYWQscHJvZHVjdF90ZW1wbGF0ZV9pdGVtczp1cGRhdGUscHJvZHVjdF90ZW1wbGF0ZV9pdGVtczpkZWxldGUifQ.Nha33G_xBC7QJDnTZOm0QF6yLp0Y0nQNt8OUUwAq4Gnn2QA5rTw8V8hUGT21Czit-Tkd47jKUQLVLNfeSY7EpkLIi557JuUeVowtTkAund28bYWqH-tOB4qCeGOR7SR3uiVrUF-sYC5AUXPd_4R0oKcEIzU_QFj_KQHtFEeId64cs3AazFuiv2MwN5E0Gkes-u1NY1YLA4GDnpauOn_NzoQt0Z9Wu8SWcb1k0QrxR5LXoLPKSZiD2KeHUYP7Mic2b8Hecp_97JGA0D9t_t9OaGOoPKW_iTNBQZFfjL5EXbJzuxfKbhOejPxZYA1wP7FF34XDMb4fRekNEdfNXgvBiPYiHkJVYJWQfWTWCbdfSj_qJyVaLO9aA9bbArBWXaqN-723Uh-yvztO-fs2fLXLNVvF18P8wNMLnkWzMlMgOJ6_e4b0ZqSZ4Vb5BUerAH5dccHNlxib7iOwqBgS1LbMZKDWe0CctiKkwsoneJ4fRRUcUxn6mJXwWk40F7U4DHp9";

        // http_response_code(200);
        // $_SESSION['username'] = "dev";
        // $_SESSION['token'] = $token;
        // $response = array('status' => 200, 'message' => 'Login realizado com sucesso!', 'data' => ['token' => $token]);
        // echo json_encode($response);
        // exit;

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (isset($data['username']) && isset($data['password'])) {

            $username = $data['username'];
            $password = $data['password'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, URL_DOMAIN . API_AUTH_TOKEN);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'username' => $username,
                'password' => $password
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                http_response_code(500);
                $response = array('status' => 500, 'message' => 'Não foi possivel realizar o login', 'data' => ['error' => ['description' => "Erro cURL: $error"]]);
                echo json_encode($response);
                exit;
            }

            // var_dump($response);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($httpCode == 200) {

                // header('Content-Type: application/json');
                $_SESSION['username'] = $username;

                if (isset($result['token']) && $httpCode == 200) {
                    http_response_code(200);
                    $_SESSION['token'] = $result['token'];
                    $response = array('status' => $httpCode, 'message' => 'Login realizado com sucesso!', 'data' => $result);
                } else {
                    http_response_code(500);
                    $response = array('status' => 500, 'message' => 'Token não foi gerado na requisição', 'data' => $result);
                }
            } else {

                // header('Content-Type: application/json');
                $response = array('status' => $httpCode, 'message' => 'Erro ao realizar login', 'data' => $result);
            }
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 400, 'message' => 'Parâmetros inválidos ao configurar a SESSION');
            http_response_code(400);
            // header('Content-Type: application/json');
            exit;
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if (isset($_SESSION['username'])) {
            session_destroy();
            $response = array('status' => 200, 'message' => 'Logout realizado com sucesso!');
            http_response_code(200);
        } else {
            $response = array('status' => 204, 'message' => 'Não existe sessão iniciada para ser encerrada!');
            http_response_code(204);
        }

        // header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
