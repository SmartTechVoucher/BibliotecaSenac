<?php
session_save_path(__DIR__ . '/sessao_temp');
if (!is_dir(__DIR__ . '/sessao_temp')) mkdir(__DIR__ . '/sessao_temp', 0777, true);

session_start();
echo "Sessão iniciada com sucesso!";
