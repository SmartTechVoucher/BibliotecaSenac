INSERT INTO `usuarios`
(`nome`, `nome_social`, `cpf`, `email`, `data_nascimento`, `telefone`, `rua`, `bairro`, `numero_matricula`, `id_categoria_usuario`, `id_curso`, `data_inicio`, `data_fim`, `genero`)
 VALUES ('Gabriel','Goat','1111111','arruda@gmail.com','2003-09-04','(67)1212121','Rua1','Bairro1','1212121',1,1,'2024-03-5','2026-01-09','Masculino')

UPDATE usuarios
SET senha = '$2b$10$O4EGO4kgSDupdLWc2qZFc.fDJiHwg4CfUms8GpTzVqqHFbwp.R/Ci'
WHERE id_usuario = 1;