CREATE TABLE IF NOT EXISTS `infinitas_jobs` (
  `id` varchar(36) NOT NULL,
  `infinitas_job_queue_id` varchar(36) NOT NULL,
  `host` varchar(150) DEFAULT NULL,
  `pid` varchar(255) DEFAULT NULL,
  `handler` text NOT NULL,
  `attempts` int(5) NOT NULL DEFAULT '0',
  `run_at` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `locked` datetime DEFAULT NULL,
  `failed` datetime DEFAULT NULL,
  `infinitas_job_error_count` int(3) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `infinitas_job_logs` (
  `id` varchar(36) NOT NULL,
  `message` text NOT NULL,
  `infinitas_job_id` varchar(36) NOT NULL,
  `error` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `infinitas_job_queues` (
  `id` varchar(36) COLLATE utf8_bin NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `slug` varchar(50) COLLATE utf8_bin NOT NULL,
  `max_attempts` int(5) NOT NULL DEFAULT '5',
  `sleep_between` int(5) NOT NULL DEFAULT '5',
  `retry_delay` int(8) NOT NULL DEFAULT '7200',
  `pending_job_count` int(8) NOT NULL DEFAULT '0',
  `failed_job_count` int(8) NOT NULL DEFAULT '0',
  `locked_job_count` int(8) NOT NULL DEFAULT '0',
  `completed_job_count` int(8) NOT NULL DEFAULT '0',
  `pid` int(5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;