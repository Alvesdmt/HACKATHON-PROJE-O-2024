-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/11/2024 às 17:29
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projecao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'HINGHEL ALVES SANTOS', 'hinghel@gmail.com', '38992200217', '$2y$10$6UzH.uCbA4.qxR0DwwdiqOiKPJNkwN11msRJkFRviJfcd.0m2Mm56', '2024-11-08 11:44:10'),
(2, '1', '1@gmail.com', '38992200217', '$2y$10$sKxZEfgZC/TC81LqpzNf4eF0uFGlE2apE8ykGeZ1o6jkiLcOcdr7u', '2024-11-08 12:57:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender` enum('User','Admin') NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender`, `user_id`, `admin_id`, `message`, `file_path`, `sent_at`) VALUES
(1, 'Admin', 1, 1, 'asdsadas', NULL, '2024-11-08 13:57:07'),
(2, 'Admin', 1, 1, '', NULL, '2024-11-08 13:58:40'),
(3, 'Admin', 1, 1, '', NULL, '2024-11-08 13:58:41'),
(4, 'Admin', 1, 1, 'oi', NULL, '2024-11-08 13:58:43'),
(5, 'Admin', 1, 1, '', 'WhatsApp_Image_2024-11-07_at_20.46.16_(1).jpg', '2024-11-08 13:58:49'),
(6, 'Admin', 1, 1, 'ola', NULL, '2024-11-08 13:58:57'),
(7, 'Admin', 1, 1, '', NULL, '2024-11-08 14:06:09'),
(8, 'Admin', 1, 1, 'oi', NULL, '2024-11-08 14:08:16'),
(9, 'Admin', 1, 1, 'oi', NULL, '2024-11-08 14:37:46'),
(10, 'Admin', 1, 1, 'oi', NULL, '2024-11-08 14:40:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `communication_logs`
--

CREATE TABLE `communication_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cps_team_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cps_team`
--

CREATE TABLE `cps_team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('Atendente','Supervisora','Aprendiz') NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `document_requests`
--

CREATE TABLE `document_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `status` enum('Pendente','Aprovado','Concluído') DEFAULT 'Pendente',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `document_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `document_requests`
--

INSERT INTO `document_requests` (`id`, `user_id`, `document_type`, `status`, `request_date`, `document_file`) VALUES
(1, 1, 'Declaração de Matrícula', 'Concluído', '2024-11-08 13:14:31', 'logo (2).png'),
(2, 1, 'Histórico Escolar', 'Pendente', '2024-11-08 13:14:34', NULL),
(8, 1, 'Diploma', 'Concluído', '2024-11-08 13:21:28', 'logo_(2).png'),
(9, 1, 'Declaração de Matrícula', 'Concluído', '2024-11-08 13:21:41', 'logo_(2).png'),
(10, 1, 'Histórico Escolar', 'Concluído', '2024-11-08 13:21:46', 'logo (2).png'),
(12, 1, 'Histórico Escolar', 'Pendente', '2024-11-08 13:22:50', NULL),
(13, 1, 'Histórico Escolar', 'Pendente', '2024-11-08 13:24:01', NULL),
(14, 1, 'Histórico Escolar', 'Aprovado', '2024-11-08 13:27:58', NULL),
(15, 1, 'Declaração de Matrícula', 'Concluído', '2024-11-08 15:34:08', 'logo_(2).png'),
(16, 1, 'Declaração de Matrícula', 'Pendente', '2024-11-08 15:37:45', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `onboarding_status`
--

CREATE TABLE `onboarding_status` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('Iniciado','Em Progresso','Concluído') DEFAULT 'Iniciado',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `requirements`
--

CREATE TABLE `requirements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `requirement_type` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_response` text DEFAULT NULL,
  `status` enum('Pendente','Em Análise','Concluído') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `requirements`
--

INSERT INTO `requirements` (`id`, `user_id`, `requirement_type`, `file_path`, `upload_date`, `admin_response`, `status`) VALUES
(1, 1, 'Atendimento Eletrônico', '../../public/uploads/WhatsApp_Image_2024-11-07_at_20.46.15.jpg', '2024-11-08 16:12:53', NULL, 'Pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('Pendente','Em Andamento','Concluído') DEFAULT 'Pendente',
  `channel` enum('Telefone','WhatsApp','E-mail') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `status`, `channel`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pendente', 'Telefone', '2024-11-08 13:29:54', '2024-11-08 13:29:54'),
(2, 1, 'Pendente', 'WhatsApp', '2024-11-08 13:32:16', '2024-11-08 13:32:16'),
(3, 1, 'Em Andamento', 'E-mail', '2024-11-08 13:32:18', '2024-11-08 13:32:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `sender` enum('Usuario','Admin') NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ticket_messages`
--

INSERT INTO `ticket_messages` (`id`, `ticket_id`, `sender`, `message`, `sent_at`) VALUES
(1, 3, 'Admin', 'oi', '2024-11-08 13:32:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_document_requested` varchar(100) DEFAULT NULL,
  `request_status` enum('Pendente','Aprovado','Concluido') DEFAULT 'Pendente',
  `request_date` timestamp NULL DEFAULT NULL,
  `last_document_issued` varchar(100) DEFAULT NULL,
  `issued_date` timestamp NULL DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `last_document_requested`, `request_status`, `request_date`, `last_document_issued`, `issued_date`, `document_file`, `address`, `course`, `profile_picture`) VALUES
(1, 'HINGHEL ', 'hinghel@gmail.com', '38992200217', '$2y$10$aKd/eyN6zqrS7XbXaUrJtOHT6fgN2t2pfx1B5QqUajftxvl8mInc.', '2024-11-03 17:10:39', 'Declaração de Matrícula', 'Concluido', '2024-11-08 13:14:09', NULL, NULL, 'logo (2).png', 'Qnb 13', 'ADS', 'logo_(2).png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `communication_logs`
--
ALTER TABLE `communication_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cps_team_id` (`cps_team_id`);

--
-- Índices de tabela `cps_team`
--
ALTER TABLE `cps_team`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `onboarding_status`
--
ALTER TABLE `onboarding_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `communication_logs`
--
ALTER TABLE `communication_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cps_team`
--
ALTER TABLE `cps_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `onboarding_status`
--
ALTER TABLE `onboarding_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `communication_logs`
--
ALTER TABLE `communication_logs`
  ADD CONSTRAINT `communication_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `communication_logs_ibfk_2` FOREIGN KEY (`cps_team_id`) REFERENCES `cps_team` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `document_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `onboarding_status`
--
ALTER TABLE `onboarding_status`
  ADD CONSTRAINT `onboarding_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `requirements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
