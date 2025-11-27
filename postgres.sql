-- -------------------------------------------------------------
-- -------------------------------------------------------------
-- TablePlus 1.3.6
--
-- https://tableplus.com/
--
-- Database: postgres
-- Generation Time: 2025-11-27 10:24:47.442561
-- -------------------------------------------------------------

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."cache" (
    "key" varchar NOT NULL,
    "value" text NOT NULL,
    "expiration" int4 NOT NULL,
    PRIMARY KEY ("key")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."cache_locks" (
    "key" varchar NOT NULL,
    "owner" varchar NOT NULL,
    "expiration" int4 NOT NULL,
    PRIMARY KEY ("key")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS categories_id_seq;

-- Table Definition
CREATE TABLE "public"."categories" (
    "id" int8 NOT NULL DEFAULT nextval('categories_id_seq'::regclass),
    "name" varchar NOT NULL,
    "description" text,
    "icon" varchar,
    "start_datetime" timestamp,
    "end_datetime" timestamp,
    "district" varchar,
    "neighborhood" varchar,
    "detailed_address" text,
    "country" varchar DEFAULT 'Türkiye'::character varying,
    "province" varchar DEFAULT 'İstanbul'::character varying,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS failed_jobs_id_seq;

-- Table Definition
CREATE TABLE "public"."failed_jobs" (
    "id" int8 NOT NULL DEFAULT nextval('failed_jobs_id_seq'::regclass),
    "uuid" varchar NOT NULL,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL,
    "exception" text NOT NULL,
    "failed_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."job_batches" (
    "id" varchar NOT NULL,
    "name" varchar NOT NULL,
    "total_jobs" int4 NOT NULL,
    "pending_jobs" int4 NOT NULL,
    "failed_jobs" int4 NOT NULL,
    "failed_job_ids" text NOT NULL,
    "options" text,
    "cancelled_at" int4,
    "created_at" int4 NOT NULL,
    "finished_at" int4,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS jobs_id_seq;

-- Table Definition
CREATE TABLE "public"."jobs" (
    "id" int8 NOT NULL DEFAULT nextval('jobs_id_seq'::regclass),
    "queue" varchar NOT NULL,
    "payload" text NOT NULL,
    "attempts" int2 NOT NULL,
    "reserved_at" int4,
    "available_at" int4 NOT NULL,
    "created_at" int4 NOT NULL,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS media_id_seq;

-- Table Definition
CREATE TABLE "public"."media" (
    "id" int8 NOT NULL DEFAULT nextval('media_id_seq'::regclass),
    "model_type" varchar NOT NULL,
    "model_id" int8 NOT NULL,
    "uuid" uuid,
    "collection_name" varchar NOT NULL,
    "name" varchar NOT NULL,
    "file_name" varchar NOT NULL,
    "mime_type" varchar,
    "disk" varchar NOT NULL,
    "conversions_disk" varchar,
    "size" int8 NOT NULL,
    "manipulations" json NOT NULL,
    "custom_properties" json NOT NULL,
    "generated_conversions" json NOT NULL,
    "responsive_images" json NOT NULL,
    "order_column" int4,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS migrations_id_seq;

-- Table Definition
CREATE TABLE "public"."migrations" (
    "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
    "migration" varchar NOT NULL,
    "batch" int4 NOT NULL,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."model_has_permissions" (
    "permission_id" int8 NOT NULL,
    "model_type" varchar NOT NULL,
    "model_id" int8 NOT NULL,
    PRIMARY KEY ("permission_id","model_id","model_type")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."model_has_roles" (
    "role_id" int8 NOT NULL,
    "model_type" varchar NOT NULL,
    "model_id" int8 NOT NULL,
    PRIMARY KEY ("role_id","model_id","model_type")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS oneri_comment_likes_id_seq;

-- Table Definition
CREATE TABLE "public"."oneri_comment_likes" (
    "id" int8 NOT NULL DEFAULT nextval('oneri_comment_likes_id_seq'::regclass),
    "oneri_comment_id" int8 NOT NULL,
    "user_id" int8 NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS oneri_comments_id_seq;

-- Table Definition
CREATE TABLE "public"."oneri_comments" (
    "id" int8 NOT NULL DEFAULT nextval('oneri_comments_id_seq'::regclass),
    "oneri_id" int8 NOT NULL,
    "user_id" int8 NOT NULL,
    "comment" text NOT NULL,
    "is_approved" bool NOT NULL DEFAULT false,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "parent_id" int8,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS oneri_likes_id_seq;

-- Table Definition
CREATE TABLE "public"."oneri_likes" (
    "id" int8 NOT NULL DEFAULT nextval('oneri_likes_id_seq'::regclass),
    "user_id" int8 NOT NULL,
    "oneri_id" int8 NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS oneriler_id_seq;

-- Table Definition
CREATE TABLE "public"."oneriler" (
    "id" int8 NOT NULL DEFAULT nextval('oneriler_id_seq'::regclass),
    "category_id" int8 NOT NULL,
    "created_by_id" int8,
    "updated_by_id" int8,
    "title" varchar NOT NULL,
    "description" text,
    "estimated_duration" int4,
    "start_date" timestamp,
    "end_date" timestamp,
    "budget" numeric,
    "latitude" numeric,
    "longitude" numeric,
    "address" text,
    "address_details" text,
    "city" varchar NOT NULL DEFAULT 'İstanbul'::character varying,
    "district" varchar,
    "neighborhood" varchar,
    "street_cadde" varchar,
    "street_sokak" varchar,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "assigned_user_id" int8,
    PRIMARY KEY ("id")
);

-- Column Comments
COMMENT ON COLUMN "public"."oneriler"."estimated_duration" IS 'Tahmini işlem süresi (gün)';
COMMENT ON COLUMN "public"."oneriler"."start_date" IS 'Proje başlangıç tarihi ve saati';
COMMENT ON COLUMN "public"."oneriler"."end_date" IS 'Proje bitiş tarihi ve saati';

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."password_reset_tokens" (
    "email" varchar NOT NULL,
    "token" varchar NOT NULL,
    "created_at" timestamp,
    PRIMARY KEY ("email")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS permissions_id_seq;

-- Table Definition
CREATE TABLE "public"."permissions" (
    "id" int8 NOT NULL DEFAULT nextval('permissions_id_seq'::regclass),
    "name" varchar NOT NULL,
    "guard_name" varchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."role_has_permissions" (
    "permission_id" int8 NOT NULL,
    "role_id" int8 NOT NULL,
    PRIMARY KEY ("permission_id","role_id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS roles_id_seq;

-- Table Definition
CREATE TABLE "public"."roles" (
    "id" int8 NOT NULL DEFAULT nextval('roles_id_seq'::regclass),
    "name" varchar NOT NULL,
    "guard_name" varchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."sessions" (
    "id" varchar NOT NULL,
    "user_id" int8,
    "ip_address" varchar,
    "user_agent" text,
    "payload" text NOT NULL,
    "last_activity" int4 NOT NULL,
    PRIMARY KEY ("id")
);

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS users_id_seq;

-- Table Definition
CREATE TABLE "public"."users" (
    "id" int8 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    "name" varchar NOT NULL,
    "email" varchar NOT NULL,
    "email_verified_at" timestamp,
    "password" varchar NOT NULL,
    "remember_token" varchar,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    PRIMARY KEY ("id")
);

INSERT INTO "public"."cache" ("key","value","expiration") VALUES ('dut-admin-cache-spatie.permission.cache','a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:39:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:13:"view_any_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:9:"view_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:11:"create_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"update_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:11:"delete_user";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:14:"view_any_oneri";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:10:"view_oneri";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:12:"create_oneri";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:12:"update_oneri";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:12:"delete_oneri";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:17:"view_any_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:13:"view_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:15:"create_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:15:"update_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:15:"delete_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:3;i:1;i:2;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:13:"view_any_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:9:"view_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:11:"create_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:11:"update_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:11:"delete_role";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:24:"view_any_project::design";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:20:"view_project::design";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:22:"create_project::design";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:22:"update_project::design";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:24;a:4:{s:1:"a";i:25;s:1:"b";s:22:"delete_project::design";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:3;}}i:25;a:4:{s:1:"a";i:26;s:1:"b";s:16:"restore_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:26;a:4:{s:1:"a";i:27;s:1:"b";s:20:"restore_any_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:27;a:4:{s:1:"a";i:28;s:1:"b";s:18:"replicate_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:28;a:4:{s:1:"a";i:29;s:1:"b";s:16:"reorder_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:29;a:4:{s:1:"a";i:30;s:1:"b";s:19:"delete_any_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:30;a:4:{s:1:"a";i:31;s:1:"b";s:21:"force_delete_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:31;a:4:{s:1:"a";i:32;s:1:"b";s:25:"force_delete_any_category";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:32;a:4:{s:1:"a";i:33;s:1:"b";s:13:"restore_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:33;a:4:{s:1:"a";i:34;s:1:"b";s:17:"restore_any_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:34;a:4:{s:1:"a";i:35;s:1:"b";s:15:"replicate_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:35;a:4:{s:1:"a";i:36;s:1:"b";s:13:"reorder_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:36;a:4:{s:1:"a";i:37;s:1:"b";s:16:"delete_any_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:37;a:4:{s:1:"a";i:38;s:1:"b";s:18:"force_delete_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:38;a:4:{s:1:"a";i:39;s:1:"b";s:22:"force_delete_any_oneri";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}}s:5:"roles";a:2:{i:0;a:3:{s:1:"a";i:3;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:5:"admin";s:1:"c";s:3:"web";}}}',1763276927);

INSERT INTO "public"."categories" ("id","name","description","icon","start_datetime","end_datetime","district","neighborhood","detailed_address","country","province","created_at","updated_at","deleted_at") VALUES (2,'Kayabaşı Meydan Projesi','Kayabaşı Meydanı’nın güzelleştirilmesine yönelik bazı fikirler...',NULL,'2025-09-25 21:00:00','2025-10-13 21:00:00','Başakşehir','Kayabaşı',NULL,'Türkiye','İstanbul','2025-09-28 10:16:54','2025-10-09 07:17:00',NULL);

INSERT INTO "public"."media" ("id","model_type","model_id","uuid","collection_name","name","file_name","mime_type","disk","conversions_disk","size","manipulations","custom_properties","generated_conversions","responsive_images","order_column","created_at","updated_at") VALUES 
(3,'App\Models\Category',2,'74330968-9a8f-4e82-a485-80c198401813','project_files','WhatsApp Görsel 2025-09-28 saat 13.11.32_dc343ad6','01K67X6KMNVF8TA0FAG8MQEEWQ.jpg','image/jpeg','public','public',408358,'[]','[]','[]','[]',1,'2025-09-28 10:16:54','2025-09-28 10:16:54'),
(4,'App\Models\Category',2,'891c2cfb-c1d3-4ebc-b16f-769dee19245c','project_files','WhatsApp Görsel 2025-09-28 saat 13.11.32_dc343ad6','01K67X6KN2CBGDSVNDHPQWKZQJ.jpg','image/jpeg','public','public',408358,'[]','[]','[]','[]',2,'2025-09-28 10:16:54','2025-09-28 10:16:54'),
(5,'App\Models\Oneri',2,'f0504b02-29e2-4fe3-877a-76fc06136d17','images','WhatsApp Görsel 2025-09-25 saat 23.29.23_a1fe5966','01K67XDZ6SACP45HPVM8Y3PNCR.jpg','image/jpeg','public','public',225133,'[]','[]','[]','[]',1,'2025-09-28 10:20:55','2025-09-28 10:20:55'),
(7,'App\Models\Oneri',3,'84e4754b-fbe4-4eb5-a0c6-42124fa0c2eb','images','WhatsApp Görsel 2025-09-25 saat 23.29.23_5b879c0f','01K67XHVSR4EFYKWFNQY5MAA6N.jpg','image/jpeg','public','public',292180,'[]','[]','[]','[]',1,'2025-09-28 10:23:02','2025-09-28 10:23:02'),
(8,'App\Models\Oneri',4,'ed233579-1951-435a-b586-033429f938b5','images','WhatsApp Görsel 2025-09-25 saat 23.29.24_b67c14ee','01K67XPW6JGZ9QF9ZDF2PA6BYW.jpg','image/jpeg','public','public',226037,'[]','[]','[]','[]',1,'2025-09-28 10:25:47','2025-09-28 10:25:47'),
(9,'App\Models\Oneri',5,'4b052c53-743e-41eb-aafb-3a900e1a7242','images','IMG_6407','01K6A5QAP8W4TS9TWRV5NPDA6K.jpg','image/jpeg','public','public',746076,'[]','[]','[]','[]',1,'2025-09-29 07:24:19','2025-09-29 07:24:19');

INSERT INTO "public"."migrations" ("id","migration","batch") VALUES 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_01_01_000003_create_permissions_and_roles_tables',1),
(5,'2025_01_01_000004_create_categories_table',1),
(6,'2025_01_01_000005_create_oneriler_table',1),
(7,'2025_01_01_000006_create_oneri_likes_table',1),
(8,'2025_01_01_000007_create_oneri_comments_table',1),
(9,'2025_01_01_000008_create_media_table',1),
(10,'2025_09_25_121657_add_parent_id_to_oneri_comments_table',1),
(11,'2025_09_25_122717_create_oneri_comment_likes_table',1),
(12,'2025_09_28_103855_add_assigned_user_id_to_oneriler_table',2),
(13,'2025_09_28_105422_add_assigned_user_id_to_oneriler_table',2),
(14,'2025_09_28_120000_make_created_by_id_nullable_in_oneriler_table',2);

INSERT INTO "public"."model_has_permissions" ("permission_id","model_type","model_id") VALUES 
(1,'App\Models\User',1),
(2,'App\Models\User',1),
(3,'App\Models\User',1),
(4,'App\Models\User',1),
(5,'App\Models\User',1),
(6,'App\Models\User',1),
(7,'App\Models\User',1),
(8,'App\Models\User',1),
(9,'App\Models\User',1),
(10,'App\Models\User',1),
(11,'App\Models\User',1),
(12,'App\Models\User',1),
(13,'App\Models\User',1),
(14,'App\Models\User',1),
(15,'App\Models\User',1),
(16,'App\Models\User',1),
(17,'App\Models\User',1),
(18,'App\Models\User',1),
(19,'App\Models\User',1),
(20,'App\Models\User',1),
(21,'App\Models\User',1),
(22,'App\Models\User',1),
(23,'App\Models\User',1),
(24,'App\Models\User',1),
(25,'App\Models\User',1);

INSERT INTO "public"."model_has_roles" ("role_id","model_type","model_id") VALUES 
(3,'App\Models\User',1),
(2,'App\Models\User',2),
(2,'App\Models\User',3),
(1,'App\Models\User',4),
(2,'App\Models\User',4),
(3,'App\Models\User',4),
(1,'App\Models\User',5),
(2,'App\Models\User',5),
(2,'App\Models\User',1),
(1,'App\Models\User',1),
(2,'App\Models\User',6),
(1,'App\Models\User',7),
(1,'App\Models\User',8),
(2,'App\Models\User',8),
(1,'App\Models\User',9),
(1,'App\Models\User',10),
(2,'App\Models\User',9),
(1,'App\Models\User',11),
(1,'App\Models\User',12),
(1,'App\Models\User',13),
(1,'App\Models\User',14),
(1,'App\Models\User',15),
(1,'App\Models\User',16),
(1,'App\Models\User',17),
(1,'App\Models\User',18),
(1,'App\Models\User',19),
(1,'App\Models\User',20),
(1,'App\Models\User',21),
(1,'App\Models\User',22),
(1,'App\Models\User',23),
(1,'App\Models\User',24),
(1,'App\Models\User',25),
(1,'App\Models\User',26),
(1,'App\Models\User',27),
(1,'App\Models\User',28),
(1,'App\Models\User',29),
(1,'App\Models\User',30),
(1,'App\Models\User',31),
(1,'App\Models\User',32),
(1,'App\Models\User',33),
(1,'App\Models\User',34),
(1,'App\Models\User',35),
(1,'App\Models\User',36),
(1,'App\Models\User',37),
(1,'App\Models\User',38),
(1,'App\Models\User',39),
(1,'App\Models\User',40),
(1,'App\Models\User',41),
(1,'App\Models\User',42),
(1,'App\Models\User',43),
(1,'App\Models\User',44),
(1,'App\Models\User',45),
(1,'App\Models\User',46),
(1,'App\Models\User',47),
(1,'App\Models\User',48),
(1,'App\Models\User',49),
(1,'App\Models\User',50),
(1,'App\Models\User',51),
(1,'App\Models\User',52),
(1,'App\Models\User',53),
(1,'App\Models\User',54),
(1,'App\Models\User',55),
(1,'App\Models\User',56),
(1,'App\Models\User',57),
(1,'App\Models\User',58),
(1,'App\Models\User',59),
(1,'App\Models\User',60),
(1,'App\Models\User',61),
(1,'App\Models\User',62),
(1,'App\Models\User',63),
(1,'App\Models\User',64);

INSERT INTO "public"."oneri_comment_likes" ("id","oneri_comment_id","user_id","created_at","updated_at") VALUES 
(2,1,1,'2025-10-06 11:27:34','2025-10-06 11:27:34'),
(3,1,14,'2025-10-09 10:44:44','2025-10-09 10:44:44'),
(5,1,21,'2025-10-09 10:59:46','2025-10-09 10:59:46'),
(6,1,43,'2025-10-10 02:48:59','2025-10-10 02:48:59'),
(7,2,1,'2025-10-10 07:15:20','2025-10-10 07:15:20'),
(8,3,60,'2025-10-18 09:10:51','2025-10-18 09:10:51'),
(9,4,60,'2025-10-18 09:11:17','2025-10-18 09:11:17');

INSERT INTO "public"."oneri_comments" ("id","oneri_id","user_id","comment","is_approved","created_at","updated_at","deleted_at","parent_id") VALUES 
(1,4,8,'Paten ve kaykay pistleri tehlikeli olabilir','TRUE','2025-09-30 08:28:30','2025-10-03 20:45:59',NULL,NULL),
(3,4,29,'Kaykay pisti fikri özellikle gençlere hitap eden, eksikliğini hissettiğimiz harika bir yenilik. Görseldeki gibi modern ve güvenli bir alanda gençlerin hem spor yapıp enerjilerini atabileceği hem de sosyal bir ortam bulabileceği düşünülmüş. Projenin açıklamasında belirtildiği gibi ''doğadan ve spordan kopmadan'' bir alan yaratılması vizyonu çok yerinde.  gençlerin buluşma noktası olur!','TRUE','2025-10-09 13:24:28','2025-10-10 06:49:31',NULL,NULL),
(4,2,43,'Meydana önemli etkinliklerin izlenmesi adına dev Led ekran koyulabilir','TRUE','2025-10-10 02:50:46','2025-10-10 06:49:42',NULL,NULL),
(2,3,8,'Mavi gölet olsa iyi olur','TRUE','2025-10-09 07:17:53','2025-10-10 06:49:52',NULL,NULL),
(5,3,60,'Alan devasa bir alan değil sonuçta bence bikaç şey olsun ama hakkıyla yapılmış duyarsız vatandaşlar tarafından zamanla tahrip edilemiycek şekilde tasarlanmış bir alan olsun (Fotoğraftaki satranç alanı mesela orası eminimki birkaç ay sonra ya parçalanmış ya taşları eksik halde olucak )','FALSE','2025-10-18 09:09:30','2025-10-18 09:09:30',NULL,NULL);

INSERT INTO "public"."oneri_likes" ("id","user_id","oneri_id","created_at","updated_at") VALUES 
(79,32,3,'2025-10-09 13:22:26','2025-10-09 13:22:26'),
(80,31,3,'2025-10-09 13:22:41','2025-10-09 13:22:41'),
(84,33,3,'2025-10-09 13:25:03','2025-10-09 13:25:03'),
(85,35,4,'2025-10-09 13:25:19','2025-10-09 13:25:19'),
(86,34,3,'2025-10-09 13:27:01','2025-10-09 13:27:01'),
(87,36,3,'2025-10-09 13:27:11','2025-10-09 13:27:11'),
(88,37,3,'2025-10-09 13:28:48','2025-10-09 13:28:48'),
(91,38,3,'2025-10-09 13:31:52','2025-10-09 13:31:52'),
(93,40,4,'2025-10-09 20:19:57','2025-10-09 20:19:57'),
(23,9,2,'2025-09-30 14:40:03','2025-09-30 14:40:03'),
(96,42,4,'2025-10-09 21:17:26','2025-10-09 21:17:26'),
(28,8,3,'2025-10-09 07:21:18','2025-10-09 07:21:18'),
(100,44,3,'2025-10-10 04:56:03','2025-10-10 04:56:03'),
(35,14,2,'2025-10-09 10:45:48','2025-10-09 10:45:48'),
(36,17,3,'2025-10-09 10:52:45','2025-10-09 10:52:45'),
(40,16,3,'2025-10-09 10:53:51','2025-10-09 10:53:51'),
(108,1,3,'2025-10-10 07:45:34','2025-10-10 07:45:34'),
(110,43,3,'2025-10-10 07:46:59','2025-10-10 07:46:59'),
(113,47,4,'2025-10-10 10:40:09','2025-10-10 10:40:09'),
(115,49,3,'2025-10-11 14:23:48','2025-10-11 14:23:48'),
(49,15,3,'2025-10-09 10:54:41','2025-10-09 10:54:41'),
(50,18,3,'2025-10-09 10:55:37','2025-10-09 10:55:37'),
(51,19,3,'2025-10-09 10:56:28','2025-10-09 10:56:28'),
(52,21,3,'2025-10-09 10:57:08','2025-10-09 10:57:08'),
(53,22,3,'2025-10-09 10:57:13','2025-10-09 10:57:13'),
(56,23,3,'2025-10-09 10:58:06','2025-10-09 10:58:06'),
(57,20,2,'2025-10-09 10:58:32','2025-10-09 10:58:32'),
(58,24,3,'2025-10-09 11:24:45','2025-10-09 11:24:45'),
(59,25,3,'2025-10-09 12:01:45','2025-10-09 12:01:45'),
(60,26,3,'2025-10-09 12:45:16','2025-10-09 12:45:16'),
(63,27,3,'2025-10-09 12:46:56','2025-10-09 12:46:56'),
(67,30,3,'2025-10-09 13:16:59','2025-10-09 13:16:59'),
(70,29,4,'2025-10-09 13:18:56','2025-10-09 13:18:56');

INSERT INTO "public"."oneriler" ("id","category_id","created_by_id","updated_by_id","title","description","estimated_duration","start_date","end_date","budget","latitude","longitude","address","address_details","city","district","neighborhood","street_cadde","street_sokak","created_at","updated_at","deleted_at","assigned_user_id") VALUES 
(3,2,NULL,1,'Eco-Meydan','oyun alanları, peyzaj alanları, bisiklet yolu ve parkı ve yeşil sistem konularak mekanın aktifleştirilmesi, cazibe kazandırılması, skooter, bisiklet gibi ulaşım araçlarıyla carbon emisyonunun azaltılması için depozito karşılığında taşıma aracı(vagon)  kiralanması 
',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'İstanbul',NULL,NULL,NULL,NULL,'2025-09-28 10:22:38','2025-09-29 07:16:29',NULL,NULL),
(5,2,1,NULL,'sadsa','sadsa',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sadsadsad','İstanbul',NULL,NULL,NULL,NULL,'2025-09-29 07:24:19','2025-09-29 07:24:26','2025-09-29 07:24:26',NULL),
(2,2,NULL,1,'Çocuk Konseptli Rekreasyon Alanı','Çocuk oyun alanları ve oturma-dinlenme alanları rekreasyon alanı',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'İstanbul',NULL,NULL,NULL,NULL,'2025-09-28 10:20:55','2025-09-29 09:22:40',NULL,NULL),
(4,2,NULL,1,'Gençlere Yönelik Rekreasyon Alanı','Gençlerin doğadan ve spordan kopmadan hem dinlenebilicekleri hem sosyalleşebilecekleri hemde eğlenebilicekleri güvenli bir alan oluşturmak amacıyla tasarlanmıştır.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'İstanbul',NULL,NULL,NULL,NULL,'2025-09-28 10:25:47','2025-10-01 10:20:03',NULL,NULL);

INSERT INTO "public"."permissions" ("id","name","guard_name","created_at","updated_at") VALUES 
(1,'view_any_user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(2,'view_user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(3,'create_user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(4,'update_user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(5,'delete_user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(6,'view_any_oneri','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(7,'view_oneri','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(8,'create_oneri','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(9,'update_oneri','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(10,'delete_oneri','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(11,'view_any_category','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(12,'view_category','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(13,'create_category','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(14,'update_category','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(15,'delete_category','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(16,'view_any_role','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(17,'view_role','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(18,'create_role','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(19,'update_role','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(20,'delete_role','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(21,'view_any_project::design','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(22,'view_project::design','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(23,'create_project::design','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(24,'update_project::design','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(25,'delete_project::design','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(26,'restore_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(27,'restore_any_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(28,'replicate_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(29,'reorder_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(30,'delete_any_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(31,'force_delete_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(32,'force_delete_any_category','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(33,'restore_oneri','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(34,'restore_any_oneri','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(35,'replicate_oneri','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(36,'reorder_oneri','web','2025-09-27 09:45:56','2025-09-27 09:45:56'),
(37,'delete_any_oneri','web','2025-09-27 09:45:57','2025-09-27 09:45:57'),
(38,'force_delete_oneri','web','2025-09-27 09:45:57','2025-09-27 09:45:57'),
(39,'force_delete_any_oneri','web','2025-09-27 09:45:57','2025-09-27 09:45:57');

INSERT INTO "public"."role_has_permissions" ("permission_id","role_id") VALUES 
(1,3),
(2,3),
(3,3),
(4,3),
(5,3),
(6,3),
(7,3),
(8,3),
(9,3),
(10,3),
(11,3),
(12,3),
(13,3),
(14,3),
(15,3),
(16,3),
(17,3),
(18,3),
(19,3),
(20,3),
(21,3),
(22,3),
(23,3),
(24,3),
(25,3),
(12,2),
(11,2),
(13,2),
(14,2),
(26,2),
(27,2),
(28,2),
(29,2),
(15,2),
(30,2),
(31,2),
(32,2),
(7,2),
(6,2),
(8,2),
(9,2),
(10,2),
(33,2),
(34,2),
(35,2),
(36,2),
(37,2),
(38,2),
(39,2);

INSERT INTO "public"."roles" ("id","name","guard_name","created_at","updated_at") VALUES 
(1,'user','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(2,'admin','web','2025-09-26 10:11:50','2025-09-26 10:11:50'),
(3,'super_admin','web','2025-09-26 10:11:50','2025-09-26 10:11:50');

INSERT INTO "public"."sessions" ("id","user_id","ip_address","user_agent","payload","last_activity") VALUES 
('NKAhZcRkqXeRYfcKOtgqUDi8kPo24Js7wyqbqhtM',NULL,'198.235.24.147','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0ZUSTRDM3BOWUZjaVc2MUtuWmxBZVRpTEU1MWE2MlZGZ2x2bXRNUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762901515),
('0grDv3Rp4KNcsoEhOxBGA6xFrFPePBfroE4tXnB7',NULL,'18.236.193.22','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkJMbG9oaVFVWmNZUXNub1hmaG9Na21UT09Xa2RaT0h6RTZla0FjRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762902102),
('tHiriT3qGrTIfkLM08dYinROhkq7oP8ooshaexAu',NULL,'98.88.137.2','RecordedFuture Global Inventory Crawler','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibXU5RzNxOUFtYTdoWnZqdE1USXBxQVZ5ZjJJa3k0R1pSMkZnWEJsbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762756526),
('c31Ii1z4YJrMnuq0stdmVLdbWgVYcdob7fcsJgXi',NULL,'164.90.211.32','Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNERXY1pMRjU1MmdLZzE0TXJHWG02c1g2OHd6YnY4MnQwd1B4SkZXUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762763003),
('UQi78pgU29roNfhMK1KMf7N58c2AmLOaOZuLEwuu',NULL,'66.249.75.128','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ1RxVUNzSWlvSDNnd0pWMU9qem9oUzRGbmpLQnpzZDlMRFVqV1JaSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762782444),
('aDFxXtC80qrhGuGUgQYkB0zuW4Lqr9GuFt1OzX2X',NULL,'198.235.24.212','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYm00dEVia01rTDRTdUNzYlE5dWpxR2QzNDd2aTJobEd0aXFYUVQ0diI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762816815),
('3EDrXV9EcGTxjxWbQkWjDWGTitOTD05yEKQMb5Wo',NULL,'74.7.227.188','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnY1alA3Wks0RmJQRFlaWGJBdHBpN3ZxS2lWM3hFQlM1NWpuMEJ6aSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762835474),
('OFJxj5qXUJyAAfq4la11i6GH2KFXQDkt20dtqf0q',NULL,'205.210.31.55','','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWNTOU51cnZibHhMU1JzeUFITWJ3Q0V1THZ0NU43ck5YQWFLV2N6ZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762837171),
('VfihfE1wg8ygt0JwrPB1v7t2qCAcfhHlb5qg9w86',NULL,'199.45.155.69','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3pLSkFrWVN6UzcxZWNSUmtrRTZHTmdHM3FFMGJ2TndRQ2lodUt3MSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762989552),
('SkmE0LGEL3C34sBsNTICz4zbVOn5t2r8McOwLMKI',NULL,'199.45.154.139','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidW0zUFJxV09uRGJ3dXdwa0pueEVudGk1TmZoSXdqaDRlajRoZXI0TCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762994407),
('tnggYbshJfwaFCHGqFVolO6LtYVqACywC7bmYg1l',NULL,'18.246.13.235','Mozilla/5.0 (compatible; wpbot/1.3; +https://forms.gle/ajBaxygz9jSR8p8G9)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGZvRGRWcDBMREhNTnpBVUJMSjhUQ2VYQ3dQUmxFMXVyd2gzbkdLQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763002760),
('zSprRH5EnPP8A1wa3pjWOrt3PskhIzkMMMVSlMRw',NULL,'185.247.137.173','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOUdYYWp6T0YyOVppMkU5djgzdG80U0xBajZUNHdCdmhIV2piTElOSCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763025503),
('9FtwmQDHZxdajG84FEOaO1tAh7eVaM7l1ecgAwy1',NULL,'128.199.104.11','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREZicFk3N0NMcW93clNPRUxsTWJ2TVFQd2dZVVp2R25uVjNQMlJjZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fX0=',1763042882),
('BNlSG1HwvSG7So7PhRZB71sbf3iutrYXZpmMvCkc',NULL,'74.7.227.153','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRElRd2dSZDJQWW92OWJUMFJIeUlNeXhRSVByU1BxbTBIeDZBZnZVeSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763058841),
('5pEETRxZuwykxWlWAQQOvrS6X4rXF880LNSl7OOD',NULL,'87.236.176.147','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVU9CT3BUOVZJUUhsbEg4ZTBXS2RUR2dOQzhWV3MyWTM0VDVvNEtuNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763096085),
('0u8rPsRBfXN4c0tBG6nrCh5EGqeisOmdmsAm0yPI',NULL,'205.210.31.109','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT1dxc2w5UWx3dm1lcW9JZm5PTllNU25GQjJDNjZ5RkN3bHZVSnNZMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763239493),
('66Bxn9gTygBElOrZp2ct2kPg0LGU0NNDx107jFuP',NULL,'176.88.76.220','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZERYeklSUWFyTm5Oa3V2MWloYUZ1WEllekZPbUZwNzVTVWxQM1ZJSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763263370),
('zgXTxTstzdhmBBAVeyCjNEZm1kplkAoUE507S77X',NULL,'98.88.137.2','RecordedFuture Global Inventory Crawler','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlFFelN4VGJpaFZFYkZEVVVzUkJXU3l5dUV3QmdSbHl4UndhV3pxayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762756542),
('pMHHy3XYEVazeZ6mS2aXO0yzahjyrHxksIhKYCpa',NULL,'164.90.211.32','Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQUdnaHZCTDVwUkVRbUVBbFlGN3BVcjNVUlV4T0gyak9iZEt3dVBPZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1762763005),
('hIIofkb01zDNUvX9KFzbIjRZXoa7Dy8jtiHMTPZ6',NULL,'66.249.76.102','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.7390.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidzZqOVFpSUpHSmttMWpvS3Y2MXhaRDFhTkd6NU9LSG9vN1lxQWQ1eCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763276328),
('vf7f4bl8facXkvK1TA0qEYQIv2DueTjYoRHiha5K',NULL,'66.249.75.134','Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVFTc0ZIMVVycWFUT2pieXk1d1hLTXdFN0drVWMwNmRnMEg0ZUhZUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762782445),
('Qvi2JVeKzNhf22peedEhT1PLrChZ6UBytYLPDkfb',NULL,'205.210.31.55','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVGVSYzA4cjh3S0ZhOU9udkJoQlBURXBRc0pvUXVaVzcweEdJdGVHMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762837172),
('Mhq11eu6ZrfJTvnGxioRbqNiHNnYXAyboEAup858',NULL,'199.45.155.69','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEUxZXB6TVY3RmZOSmpEYXV3VnptYUJiNTF3bTdRb0taSkszZDRMbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762989556),
('stVDT0YS0tdw0wCrio1OuFFLd0FjRbjefRhriUYM',NULL,'199.45.154.139','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXozMWxvajZ0Y28yRlhSUVNEWkxkQ1I3aEd0bTRpQjZkNWlnWUdEZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762994410),
('xKOvjy3Cz77iDgiINnDGxb2iFGqhtUKOmacuqg9b',NULL,'87.236.176.147','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHVpT3plenc5eTRXeUFpcGFmZTRDMWNPeFJVOVdFc1JuWFhwVzVobSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763096085),
('47kDSb4gK6Xlf2MnYNUj6VnHDKjrKXlKx8zWw46p',NULL,'95.70.130.211','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSlM2MDFGZldLeHI4M25YUDJJZXQxdThsQjV4MkI5am5lZTNpUWxQZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2FkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763107384),
('ysEpG085X1H01qDYmg8pmD1sGwTqryJpfHrDRwbe',NULL,'13.158.130.105','Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0.4472.124','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSkl3aUl6aThTU2lnMUtpNXcwMkh5V0h0VUVkN2NlTFc0MXJ4aHNWaiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763139989),
('hdTRPj4s3rTgK3Z5kf4467J6oJ4liTW8IIAa6o4X',NULL,'198.235.24.15','','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicjVZRHZ3dHl2N3hNRG1JSDZBbEY4Smg4ZjR4cTVoa0ZBZ2w5dThMQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763432097),
('zAOq4OXMy5zANmKN4z7yeCopedawRBQBLsX9uJgl',NULL,'205.210.31.212','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzVaUVp5aXI2bktVdjN5S25wckJrNlNrU3JiQldBTERwdWVGQU1sUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763149049),
('jnyEle23NebxUgyblV0mLDOxwTKvr6mXJDkGx5Rw',NULL,'34.245.73.144','Mozilla/5.0 (compatible; NetcraftSurveyAgent/1.0; +info@netcraft.com)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNXpSTzFUMDBrZXE1UUZqWmswSG1UU3p4UkcyaG5iUXVmb0poaThGNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763157085),
('nEaGwtufHz467IXQwvZqECCeUo5PUOD3uWAchdye',NULL,'204.76.203.25','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.3','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjBsb1ZXd096ZUdFOGlsMUhCZjdPYlBaZVBoZHdaelBYM1ZTVWQwZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763464419),
('gPk88FtGfb54pBkmFpNMdI7SNbV9bh2DMPGAZPi0',NULL,'95.70.130.211','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibm41S1o3eWc3N29ueTlaR1hsSmNGSUpoNlN2MTNxMzhHSzdzZmZVaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvYWRtaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763107384),
('xRMJyNeFqeUbUEL9fC4BBG771u5flZxLzDjYQrRT',8,'88.230.93.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoidXVhRGdvN1d6eE1Zd3JEb1BEazNUbFFxd3lDd1pnaExVaHFrOUZveSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJFlTLi5NQ3ExQ200cGRUVGJ4aEVvVWUuOC5nT2FIN0VkWjFXbUFORUhKMDhaeDUuVG9VRHJ1IjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1763192327),
('kTSHWAgEFGO7sFFZq1UL1LhlISG6LKaA8Xu2cK7G',NULL,'198.235.24.15','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ2Z6TzJnaUtpMDByNEtId0VKUGh4V3ZkRU0wZ3FrbnVIbGxTRFBlOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763432097),
('wivJxiJBquMdLmt9bxRJcl9vMQx5VytIcNsgDXsB',NULL,'74.7.227.201','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzVocFUySDhKY2FZekVRYnhCV2s4WUJwNUJPZ2t6cGlYWVVKUmpXciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763461475),
('MB6QWNxcDnkFcbO0vXndlAeawRB25QecvSBGLMa4',NULL,'204.76.203.25','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTBFMjhNTk85S0xlWVJkbm56ekNZVFNqQkxPVEtJRERFQ3g1dDgxcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZHV0LnZpc2lvc29mdC5jb20udHIvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763464419),
('AQzggjXgXFdqjZVq8ZUl0vBtVnitUQvxHg8Z6X66',11,'104.28.244.150','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRWFONmNScVhxUm9rd2VTVkh3SE5Da3NaWEp3MG50VVlWRTZQcGg1eiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7fQ==',1763562746),
('nzVAYkZzvwBNBMcaSdyxMJ22GD14Rml5lpaC1VIw',NULL,'98.92.141.164','Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYTRJTkNob0NhSmlMRUliQXNsRENwVUpPR0RKUmZUWUtUTUtNV1M3VSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyL3Byb2plY3RzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoibG9jYWxlIjtzOjI6ImVuIjt9',1763547911),
('BeeIbgy1LlLk5WqKyaZiaK93VFwLgPWAmlTiKFWH',NULL,'205.210.31.66','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVTZDUGRJMmttUmxnb0F5bXVGRDVGZE5IWUZrWU5WWkVYUHVMaGRxNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763583964),
('iygrFrcOXJexttbohYcP7DaRyxZG7gL8iSgWG570',NULL,'159.146.45.156','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVk3Y0s1SjFTU0FyOUpjUGZNZlkyWkpvd0hha3RJVDBCY3o0aVU1cSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9wcm9qZWN0cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763565972),
('Ek8BcK7DF5A9cV2dJsAfMbKv9zO7ooFLbEcMGiPa',NULL,'152.42.177.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4240.193 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVVIV1h2RWlKNGZsa3oyMHd0OXNLWnpIQnBjWEVqM0xoZG9ORlJERyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763615824),
('GgQ0EYevfrdgXAxu4FEY89nLT0VShlK3EtcsBSl0',NULL,'74.7.241.17','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUp2cXM3SFFMOXZmT0lET2xGZENlR1c0SUdFUEJVRERzZ284eEk5ZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763800555),
('FJxVFXrvkrdN0BQsKGEZiZxYq7IE7lztKAxPKhdl',NULL,'173.252.127.115','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidTdoMzFvVG5UcUg4SVVLYWp2VDJNOXRVaGNwUDYyME0xRnFVMmhVRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763815932),
('nPZZOtU4K1ImRsJyu9M9u4lMu6uKMzkVVrnpqyPp',NULL,'173.252.127.10','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXBmYW1YcnAxcXo1QjhIM0pKUWdKcDU0MVVPWXByZjBITDFhQ0JyayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763815933),
('DSq5qIYdLrmXJZAkMb3uTXQLLX5Y8XCy2do4qdiT',NULL,'173.252.127.36','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSmxpM2hZSDZ6THg1M05kaFAya2Q4bmtLUFJmem5kNk5kaTVpSVNVbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNzY6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci8/ZmJjbGlkPUl3WlhoMGJnTmhaVzBDTVRFQWMzSjBZd1poY0hCZmFXUU1NalUyTWpneE1EUXdOVFU0QUFFZWJkUHZfcFQwZm15QUdRb3BJOThTZjdrZEJndmlnYmVQTTNnT0stcU5sVmRHZDlUaEowdHJ2MXU2cnp3X2FlbV9BVmt6T3M1TnhjazFMM18tSU5la3FnIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763815935),
('tozAUo7ZOhF2UVtXYCjY2ONTfFzalkClHbR2Hft2',NULL,'173.252.83.3','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRFVrdnlleWxKUlVlQzVGRGR0TlZJTHlyR3ZQOTladHhrdmNrMjZuNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763815940),
('ACi1LB5GkfNTcEEV84Jy1X7UVgM1mxWPxEZVRXBs',NULL,'173.252.83.8','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoidlBBb2ZQN3RxOWVENnBkSERMOU95UGZ0T1k0cDlsUlBSYmVFSlNOViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763815941),
('KbdTc4K6mowTUvirVYJCQMj4scdBXneirreSI4oF',NULL,'34.141.243.206','Mozilla/5.0 (Macintosh; Intel Mac OS X 12_0_1; rv:112.0.1) Gecko/20100101 Firefox/112.0.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1p2bmdJakR0Y2VEemUxWjFwS3VLN1lZZWlJME8yM1hsTHFnM1V4NiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763884992),
('snQ0CtDyNSW9cUVBScXdi5dJf4PCM9JeRKL3E7JV',NULL,'35.176.165.207','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:128.0.2) Gecko/20100101 Firefox/128.0.2','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDJLa2JyZWl0M0lXMnVWOXRidk42VVZQUXoxNmE1bDBuNXQ5cWVpeCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763884993),
('ub4XRrFJpiVTIOW1bbESYcQwvfx0pVkWjouXVnO9',NULL,'172.104.245.139','TLS tester from https://testssl.sh/dev/','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZUxqcHpuQXE0ekFISlV3TFJBTFc4dlJQS2ZvTXN1cElNc3RCRkF6VSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763890796),
('wlQvHDY3Sxv0C2N7WVW7Xe55nuMWZGz26HtJqGjE',NULL,'172.104.245.139','TLS tester from https://testssl.sh/dev/','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNlhHTnNzbmlCbmJuTDZhbHZqd0xpZTVISEtsZ3RhU0NDNGxJY2FOZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763890860),
('68X7WYb7tU2cCpnOPhvuIDyJOJqIQtviHnSRty1l',NULL,'172.104.245.139','TLS tester from https://testssl.sh/dev/','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSUxRb2F5YTdyTTZrTnV6dnRidjMyM01JSzVNN04xWjNmc01wRlBIQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763890861),
('DI7Du1i3i4j1rhgC5YC5pZYonyFYaCiK8y6P068R',NULL,'172.104.245.139','TLS tester from https://testssl.sh/dev/','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUNvY2FaSnNXNzQ0blRDUURuY21teFRSWXhaOXltdDIyTFh6eVFyRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763890872),
('22eXKh7nRga0xaFvsdHmvsywhk4YAlG9ZAMcFxb3',NULL,'78.182.151.216','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSU1HRmlLZTN2ZGJtTGhpQnluOUlCVTBORG5OcjVUak10TmxKQldiUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763911448),
('hJQeu23vNMTXtpLgth3vjw2KmisSm31tJ2FT1LxV',NULL,'139.59.90.171','Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSWt2bHZmTUlIazRpTzl6Q2lrNk80bGZyWXZiR0dHSW9pVDRMSXVWSCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763966504),
('eSUVsLplbO6n8XdrAfI107YoLrkHT1F273l6k24y',NULL,'139.59.90.171','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDdMQXg3RlFiMFdhamtvTWdRSXdHU043STFNaWtoSExUTllFak1lbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763966509),
('xhxQ4gu5jZD998dJsNB4uzug1lCIlDZFfLITAJmy',NULL,'3.146.111.124','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibmpNQWpTQUxOanFUZndsTGh1elo3NFBYeUpFRENkNzBXMjNMcXpGbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763968607),
('AqFcob2y6XcKrG74bRW0IfdXEndve6RwqySnmMJQ',NULL,'3.146.111.124','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia29ZZVRaQU9nWXlBSzhnYzg2cmxyblBoMEcwbEY5eDNPVm80cjFTNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ci9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763968607),
('53GTddvuh8E14FFanLugcuOowSvRFPoRGEj5Lbzk',NULL,'64.23.235.33','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUldMUHBNVHRkOGtmVWxvdjRNYXo5MGZ4VkRxRUF4SEpZc2FGOXc0dSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2R1dC52aXNpb3NvZnQuY29tLnRyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763975060),
('uiMJenK5SSmz5t50nYa1nEcci8r1AdOA560QYNYj',NULL,'46.106.252.157','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUDIxOERUSEEzZUt2WFRTRDRYWXRqOWRsb1RvNEVyZm85MHZEb0s4cyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1764011001),
('HDlNuGYozbFkzUmQbaCYOhXDQ3q8cqn3kEEbqdDI',NULL,'74.7.241.17','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSll6b0Rsemp1NkhzWTlJWGF6RFl0Y01jRFJYSVNibUVmeUFJa3FYTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9kdXQudmlzaW9zb2Z0LmNvbS50ciI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwczovL2R1dC52aXNpb3NvZnQuY29tLnRyL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1764056322);

INSERT INTO "public"."users" ("id","name","email","email_verified_at","password","remember_token","created_at","updated_at","deleted_at") VALUES 
(23,'ENES METİN','enes.metin@basaksehir.bel.tr','2025-10-09 10:57:40','$2y$12$UeyOlZhx8IdUpMGvygTVOuTUmn4Fkk.Z2a59/L/wmF2DUR8LM72MO',NULL,'2025-10-09 10:57:40','2025-10-09 10:57:40',NULL),
(4,'Cihan Topaç','cihan@visiosoft.com.tr','2025-09-26 12:29:07','$2y$12$0gOw5HXL/K.GksVHnrWnLO00Q53WqKLlkTaijT6hAO1FKO7X7SOAS',NULL,'2025-09-26 12:29:07','2025-09-26 12:29:07',NULL),
(3,'Normal Admin Main','normaladmin@dut.com','2025-09-26 10:11:51','$2y$12$1sEKihdYlHDMLECNoxA8ReGegimCJ8H3.2ezzs6jtRFSUa9.S6YI6',NULL,'2025-09-26 10:11:51','2025-09-26 12:29:08','2025-09-26 12:29:08'),
(2,'Omega Admin','omega@admin.com','2025-09-26 10:11:51','$2y$12$.6uVHt0/.qLDKcuQ0bciBOdLKDHK5oiEkGtmnz/32aYUACiVar7GC',NULL,'2025-09-26 10:11:51','2025-09-26 12:29:11','2025-09-26 12:29:11'),
(5,'Batuhan aydın','bbatuhanaydinn@gmail.com','2025-09-26 12:33:05','$2y$12$iVBpOw0iw3bU.ppCJCba7.RQUUYnEXMkJFcVOeuZUqUiJvLvVJVzW',NULL,'2025-09-26 12:33:05','2025-09-26 12:33:05',NULL),
(24,'Vahit GÜNÇİÇEK','vahit.guncicek@gmail.com','2025-10-09 11:23:28','$2y$12$KcKn63P.kbdXSperZlqrKePFS0U2QFeoINvY5nKUlUBz.VLk3nKOS',NULL,'2025-10-09 11:23:28','2025-10-09 11:23:28',NULL),
(25,'Nazli Savul','nazlipurde@gmail.com','2025-10-09 12:00:29','$2y$12$8y7sxPIeZTANfoVZHxihlu1YqEKJ0n1LHl.0JHr2.42UvGXMvfwVe',NULL,'2025-10-09 12:00:29','2025-10-09 12:00:29',NULL),
(26,'Samet yardım','sametyardim0234@gmail.com','2025-10-09 12:44:51','$2y$12$oUwpDggGc33KNmZgnkIMVe4TKzpR.FEzb9M6/qo5meuWP4NvxK4nK',NULL,'2025-10-09 12:44:51','2025-10-09 12:44:51',NULL),
(27,'RAMAZAN BİLİR','ramazan.bilir@basaksehir.bel.tr','2025-10-09 12:46:18','$2y$12$j3xD6mFhd6UXRkVSTxCWOOlwECElUFQDieCjqswIntBL7AJIXVkb2',NULL,'2025-10-09 12:46:18','2025-10-09 12:46:18',NULL),
(6,'deneme','admin@admin.com','2025-09-27 09:46:16','$2y$12$0pRbC3.GZuxLaRjhCcOKL.rVnQNO4CpWQ2A8po8tf0VazLgNVqIfy','9Q7341Fs1xeJSzlqGxfjYR0l52MNC2l7JfRD7RVAOJuSRLLqCZuNU2MMaqL5','2025-09-27 09:46:16','2025-09-27 09:46:53','2025-09-27 09:46:53'),
(7,'deneme','deneme@gmail.com','2025-09-29 09:16:23','$2y$12$ydAPwpe.W3iTAZuFEmZuwOHxdInFwweergDCqzxpJ6/DdJK87cByu',NULL,'2025-09-29 09:16:23','2025-09-29 09:16:23',NULL),
(28,'Ali Şimşek','deslateralus@gmail.com','2025-10-09 13:15:35','$2y$12$kZrzXcHyt8CUZHXzpoKXbOkoFHOXQO6pIOOsmupgaB5B9RtrEWw5K',NULL,'2025-10-09 13:15:35','2025-10-09 13:15:35',NULL),
(11,'Yasir Tapar','taparyasir@gmail.com','2025-10-07 17:48:33','$2y$12$rPw3VU8lqUhaH3tv6AidpuxwrKUpMNr51vXul2sOPkM9isKJEseRW','dn8vmSpa1VJanPjQUuKdupBtFk0S8Wuzoeqx07CchRPNp9U7LyO3IbBk9PGT','2025-10-07 17:48:33','2025-10-07 17:48:33',NULL),
(29,'Ali Öztürk','ozturk.ali@basaksehir.bel.tr','2025-10-09 13:15:50','$2y$12$/EWpHEDbAC0QgwZSJF0KzuwR7tr.S/bPxp2K/0C6L1BCa/0Qv1cFa',NULL,'2025-10-09 13:15:50','2025-10-09 13:15:50',NULL),
(10,'Alain Renk','herbillon@me.com','2025-09-30 22:17:02','$2y$12$Z.waG5SrpFaUDyfPaqZuvOb1CFFR0jJxrMvcfSRB9K2tilxDN7Lea',NULL,'2025-09-30 22:17:02','2025-09-30 22:17:02',NULL),
(9,'Eda Elmacı','edaelmacii@gmail.com','2025-09-30 14:38:43','$2y$12$aUpqSbLi.nM1ZF0.lX349uFw3r8LpH7AtVhf3z.2uu2LDdFmw8wkS','nsKJEnbETOlGD3bxBOZab9BJlILGPPdGCOzSexxzXnupO1vM6W1YnYuqGvLR','2025-09-30 14:38:43','2025-09-30 14:38:43',NULL),
(30,'Servet YILMAZ','srvtylmz58@gmail.com','2025-10-09 13:16:06','$2y$12$mmoGoV3oP56oniJ.vnOXve4XV8UXA64iwmcWEgATPU9MfH2mRtGna',NULL,'2025-10-09 13:16:06','2025-10-09 13:16:06',NULL),
(12,'Ahmet SAYGI','ahmet_saygii@hotmail.com','2025-10-08 08:48:21','$2y$12$yt8pGjs.Piv41C9VZdz/4.BXb/5dFm6CiQD5/06EVXk64ocdvJG5C',NULL,'2025-10-08 08:48:21','2025-10-08 08:48:21',NULL),
(8,'Ömer Karabayraktar','omer.kbayraktar@gmail.com','2025-09-30 08:27:28','$2y$12$YS..MCq1Cm4pdTTbxhEoUe.8.gOaH7EdZ1WmANEHJ08Zx5.ToUDru','yh8sWuOwH0vpmzhoNDnpexR7lpD3ORrzEUBeIHIqUirnIMw1BxivEAES2Rdr','2025-09-30 08:27:28','2025-10-07 08:17:49',NULL),
(13,'Ömer Onur','omer.onur@basaksehir-livinglab.com','2025-10-09 07:22:07','$2y$12$EzyqGf983EY7f5UK6EUsfeeQUQTMNHYm2fp9l8fUcCLR9SQV38ZYG',NULL,'2025-10-09 07:22:07','2025-10-09 07:22:07',NULL),
(14,'cengiz Arslan','cearslan@gmail.com','2025-10-09 10:43:59','$2y$12$mmAV99UU6kWkGd4pjcZdkOErBt.7DINJspeKqDovLyWNapmtx3Yni',NULL,'2025-10-09 10:43:59','2025-10-09 10:43:59',NULL),
(15,'Küçükkara','kucukkara53@gmail.com','2025-10-09 10:51:56','$2y$12$AGOm2TQhk2A4sykrRefffO5S/Plgkz46oWkya7KqoCQUTjibdqQPm',NULL,'2025-10-09 10:51:56','2025-10-09 10:51:56',NULL),
(16,'soner bayar','soner.bayar@basaksehir.bel.tr','2025-10-09 10:52:30','$2y$12$h5HKlK8apYUnnT6DTZVnBeMfSeyhcGyQHcPXJfaYBukqzV.n89piW',NULL,'2025-10-09 10:52:30','2025-10-09 10:52:30',NULL),
(17,'ORHAN ARSLAN','orhan.arslan@basaksehir.bel.tr','2025-10-09 10:52:35','$2y$12$6lB49jlJ61hd9GwVPYMrgeFFDnHlOkXN1ePp3KBhzcJ1xngBCHPia',NULL,'2025-10-09 10:52:35','2025-10-09 10:52:35',NULL),
(18,'Furkan Eryiğit','furkaneryigit0202@gmail.com','2025-10-09 10:52:53','$2y$12$8S.BhnXvbJanhXVTa3pI3uL5s3dRhwk7vj0ZLfHzYKiBH8Rw5t1Ky',NULL,'2025-10-09 10:52:53','2025-10-09 10:52:53',NULL),
(19,'sultan köseoğlu','sultan.koseoglu@basaksehir.bel.tr','2025-10-09 10:55:19','$2y$12$SGGN2wQ.K8kUVrONUo7VrePDdjpYFNa/r6ud0aHXftCQQEkvdIkOS',NULL,'2025-10-09 10:55:19','2025-10-09 10:55:19',NULL),
(20,'FURKAN GÜNCÜ','furkan1334gnc@gmail.com','2025-10-09 10:55:24','$2y$12$hFsay/cmOeI/D68OtT9UxOrGdCJXhl656ew7qJ6R5E.sCWehEJpEu',NULL,'2025-10-09 10:55:24','2025-10-09 10:55:24',NULL),
(21,'gülşah şentürk','gulsah.senturk@basaksehir.bel.tr','2025-10-09 10:56:13','$2y$12$.cfEjAr1D1DjyEmxpdDgbert.YyIObfOqAbtka/jSXlBB1mszdeha',NULL,'2025-10-09 10:56:13','2025-10-09 10:56:13',NULL),
(22,'Mahir Göze','mahir.goze@basaksehir.bel.tr','2025-10-09 10:56:57','$2y$12$TXBvbdw.Q5.X/MXF5yPp4OYf1/cBIb671//.kpGWIlFFaOyWRTMbm',NULL,'2025-10-09 10:56:57','2025-10-09 10:56:57',NULL),
(31,'Fırat Can Antlı','firatcan23_12@icloud.com','2025-10-09 13:16:48','$2y$12$j6RGXCEINLR8GWHjt.EY7eWkLQHf7ZIly/8YMfL4bEPEFv3UzQOjO',NULL,'2025-10-09 13:16:48','2025-10-09 13:16:48',NULL),
(32,'HAKAN ÖNER','oner.hakan@basaksehir.bel.tr','2025-10-09 13:19:58','$2y$12$1WcAbzVtIPuwgG97rs2dDO.5ynFtU7Cb6uHI/41xuer2OAjoQf2NS',NULL,'2025-10-09 13:19:58','2025-10-09 13:19:58',NULL),
(33,'ferhat culha','frhtclha58@gmail.com','2025-10-09 13:23:28','$2y$12$gpajaoYszarILb66WD/FauaBKsbNg4i30nTj5bTn9heUNw6uy5RAO',NULL,'2025-10-09 13:23:28','2025-10-09 13:23:28',NULL),
(34,'EMEL CİHANBEY','emelimkaarsiv@gmail.com','2025-10-09 13:24:10','$2y$12$kZy5DMIr6lG2XgpGY2Gg8uJNmlOtOKl3BFP8HaQ./iSnAWKuAs3oG',NULL,'2025-10-09 13:24:10','2025-10-09 13:24:10',NULL),
(35,'Murat Karayel','murat.karayel1977@hotmail.com','2025-10-09 13:24:41','$2y$12$CBc.2B3k4HBoA0WYjSlDw.HI9Ps7ZDToLZjBdoXfe8D7aoL4AYVM.',NULL,'2025-10-09 13:24:41','2025-10-09 13:24:41',NULL),
(36,'ramazan akinci','ramazan.akinci@basaksehir.bel.tr','2025-10-09 13:26:57','$2y$12$A7/D8hiRM3fhZrJbR5cd/.wGRspvgOuJay8UH0irWIZnWsAfUYz9K',NULL,'2025-10-09 13:26:57','2025-10-09 13:26:57',NULL),
(37,'ahmet demirdağ','ahmet.demirdag@basaksehir.bel.tr','2025-10-09 13:28:24','$2y$12$Bo0o.o7j2.ddx8ngz6UOmeJ2uoczxnLctDHc3g/7lNYS7Rb3IOJU2',NULL,'2025-10-09 13:28:24','2025-10-09 13:28:24',NULL),
(38,'ATAKAN AYDIN','atakan.aydin@basaksehir.bel.tr','2025-10-09 13:30:52','$2y$12$ufFEbVioRhA/OSBPi.FNjeAURmQH1GXYUl8j9oBoKFgqdDHu0NpjC',NULL,'2025-10-09 13:30:52','2025-10-09 13:30:52',NULL),
(39,'Üzeyir Yıldırım','uyildirim1@gmail.com','2025-10-09 14:31:44','$2y$12$ODlj67znNhxfggtZoMc2BOmQzb4tZY0InHvDsc5/.2JtN0JpakZeG',NULL,'2025-10-09 14:31:44','2025-10-09 14:31:44',NULL),
(40,'Hadiye Selen Kaya','selenkaraca@windowslive.com','2025-10-09 20:18:50','$2y$12$9CQ3MgqUwL9SvdmXegvxh.XAvRLF..igL.U0f9oAlDzC/xy2laU6G',NULL,'2025-10-09 20:18:50','2025-10-09 20:18:50',NULL),
(41,'Pınar Özcanlı Baran','peroran@hotmail.com','2025-10-09 21:07:58','$2y$12$o56xMQXNReclQyvPcmjb.OqH8FAGLwe8Mvi9/xqzy0DltBXNPAgUa',NULL,'2025-10-09 21:07:58','2025-10-09 21:07:58',NULL),
(42,'Robamansour','robamansour@yandex.com','2025-10-09 21:16:49','$2y$12$8SmfKzxoasz3VTlXXI3sKu.yd/aEJPjm1gyKgHpdy2sHHW1Y0jXeW',NULL,'2025-10-09 21:16:49','2025-10-09 21:16:49',NULL),
(44,'Özlem Soysal','ozlemsoysal.ceo@gmail.com','2025-10-10 04:54:23','$2y$12$ZAfe9ibV3xf4fPjhGP.11OH2BpliSAo1uUXAF/QaTXGgFyjBUw0q.',NULL,'2025-10-10 04:54:23','2025-10-10 04:54:23',NULL),
(1,'Polat KARGILI','polat@visiosoft.com.tr','2025-09-26 10:11:51','$2y$12$uShIf2EIX.Cr8Dwikvms..K6xvu9r./jSIPs2MfIwupJm/8rSPRJC','XZX0r4U6dwnDkFNwblyubEbhrdQ3aS5Mda0S7ozvK9ZH00VkQnthohwOzXJ3','2025-09-26 10:11:51','2025-09-26 13:01:39',NULL),
(45,'Hasan Hatipoğlu','hasanht78@gmail.com','2025-10-10 05:52:02','$2y$12$3HVijhPKe59DNiUSURrtX.kfLBgjlPjOYmempogYhrx2nH5ZOehai',NULL,'2025-10-10 05:52:02','2025-10-10 05:52:02',NULL),
(46,'Serpil Tarhan','trhnsrpl@gmail.com','2025-10-10 05:56:56','$2y$12$mnMGecPqT5sP/X9OhlHKfOizBr8ZMxzOCwNp8R3BDXrRD6/51Othy',NULL,'2025-10-10 05:56:56','2025-10-10 05:56:56',NULL),
(43,'Bekir selçuk temel','bekir.temel@basaksehir.bel.tr','2025-10-10 02:47:29','$2y$12$GzyBE1CsCSJsn07jv4IW/e1qpB0QDrENl.Dp1m60tZ4QVaie9vAV6','uOeug77eYr7dBXvKjwWmmU6KSY2eSfvO0o8uQvXaycU1ffleLd96X7ywcDmm','2025-10-10 02:47:29','2025-10-10 02:47:29',NULL),
(47,'Müberra SAĞINDIK','muberrasagindik@gmail.com','2025-10-10 10:39:13','$2y$12$QQk9TDXJhb8C1UhiA7w9Luvx1nzMvRGIYOqtp1IdET118RLgz766G',NULL,'2025-10-10 10:39:13','2025-10-10 10:39:13',NULL),
(48,'Kevser Savaş','imran2009fatih2013@gmail.com','2025-10-10 21:19:50','$2y$12$hjTT4cGYPdwlLYbmjoekgurdvGcDALCeqxZwlDCaJn38cDJiPJNWC',NULL,'2025-10-10 21:19:50','2025-10-10 21:19:50',NULL),
(49,'İbrahim Karakuzu','ibrahimkarakuzu@gmail.com','2025-10-11 14:21:42','$2y$12$BKhMG0LzgBJ1wP6NRpBjpOZtJPRyD2MsnrCRZQ1tOEDPTLpNuQOX2',NULL,'2025-10-11 14:21:42','2025-10-11 14:21:42',NULL),
(50,'Yusuf tellioğlu','yusufoztel@gmail.com','2025-10-11 16:44:55','$2y$12$bsrKGIZCmkCKV8tKXsv8yOo6RnRx.4otHNgtYr/VxNtGesrXvWsqW',NULL,'2025-10-11 16:44:55','2025-10-11 16:44:55',NULL),
(51,'Gülseren Şahin','gulserensahin0552@gmail.com','2025-10-13 09:33:00','$2y$12$UWMMQSaY9a5YS2UcwKUgIuWAVZ1RhhkUcgJ0zDsvHavewLZVKPqHe',NULL,'2025-10-13 09:33:00','2025-10-13 09:33:00',NULL),
(52,'Fatma  Akköse','fatmaakkose94@gmail.com','2025-10-13 11:28:40','$2y$12$/.xEqt1J56BMwSCtDgMtE.eBKUOkGmAQceNJI7CbFhj0XqxCZL6MO',NULL,'2025-10-13 11:28:40','2025-10-13 11:28:40',NULL),
(53,'Hüseyin Karabulut','hsynq@hotmail.com','2025-10-16 16:53:32','$2y$12$O1tUqm7D00JnChJ9mu3uJOf2fkk5JLUm4dRx6tEo9HroIotqC4YRK',NULL,'2025-10-16 16:53:32','2025-10-16 16:53:32',NULL),
(54,'Nuriye Sezer Okur','nuriyesezer@hotmail.com','2025-10-16 21:52:52','$2y$12$EnKv1cvcc2OuPmdSWfuP8Oaw5HQDnP2SJbGZfB2Da7NMd/J1s0G0u',NULL,'2025-10-16 21:52:52','2025-10-16 21:52:52',NULL),
(55,'Elifnur Sekman','sekmanelifnur@gmail.com','2025-10-16 22:02:53','$2y$12$.0y7TYx32WK9Bsh6RjPDFuAC5TbQNJ7J2Tc9F2UnURfennp6XtSpi',NULL,'2025-10-16 22:02:53','2025-10-16 22:02:53',NULL),
(56,'Meral Alkan','meralalkan81@hotmail.com','2025-10-16 23:01:10','$2y$12$bjmAo/0gIMH9ZS/.YievguLcX.KeMSNJNYKr43aMssmHKwT2KLiGu',NULL,'2025-10-16 23:01:10','2025-10-16 23:01:10',NULL),
(57,'Eylül Güngör','hello.eylulg@gmail.com','2025-10-17 11:37:05','$2y$12$pnCt173ti79D/XMGWbSjFebH3JahOhT.gTb2XeDYYhYpgnmWvs6l.',NULL,'2025-10-17 11:37:05','2025-10-17 11:37:05',NULL),
(58,'ÖMER ASLAN','omer_aslan_92@outlook.com','2025-10-17 14:29:41','$2y$12$Po.Us/U0SbLc3ESZcWXx9.FBA5hAiYtivj8b1oAqrB8nNqRXSPDia',NULL,'2025-10-17 14:29:41','2025-10-17 14:29:41',NULL),
(59,'Ahmet Miraç Berber','ahmet28mrc@gmail.com','2025-10-18 08:20:45','$2y$12$h7G0oNzSbVekkdaiwv3A4O97x3JZkapJQ4ETAPG28YuZXZ8Hoc52q',NULL,'2025-10-18 08:20:45','2025-10-18 08:20:45',NULL),
(60,'Laith alsalamah','laithalsalamah2020@gmail.com','2025-10-18 09:05:36','$2y$12$Fgf036xidk.9.c7XIMXZUOSJC.AKWObkRIvtkzvWc40U6sWWzNY3q',NULL,'2025-10-18 09:05:36','2025-10-18 09:05:36',NULL),
(61,'Büşra gürbüz','busragurbuz034@gmail.com','2025-10-19 09:28:47','$2y$12$.gunPClDhr4/MDwpqmT48ur3y/GIpjL8GV0S72mP.uOOAIOQelVNu',NULL,'2025-10-19 09:28:47','2025-10-19 09:28:47',NULL),
(62,'korkmaz çakar','korkmazcakar02@gmail.com','2025-10-19 11:04:41','$2y$12$YRG0KDodNE14mtG9Zfm4x.8L/jyj73DokY6rVU8z2kWs4TGzsLVUy',NULL,'2025-10-19 11:04:41','2025-10-19 11:04:41',NULL),
(63,'Alanur yaman','yamanalanur34@gmail.com','2025-11-04 16:29:15','$2y$12$7qPa.7xf7UdstMcM0VxMSuiozq.7MN83fbq6s2ZsVr6yazg4vO/m2',NULL,'2025-11-04 16:29:15','2025-11-04 16:29:15',NULL),
(64,'Ömer kazan','omrkzn@gmail.com','2025-11-08 20:12:42','$2y$12$W3z9bBHLazvyZdQS0boI2OS1FjJ5m5hF5CzCDP13pP1lcff3toJB.',NULL,'2025-11-08 20:12:42','2025-11-08 20:12:42',NULL);

