<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostgresDataSeeder extends Seeder
{
    /**
     * Seed the database with data from postgres.sql
     * Maps legacy tables to new structure:
     * - oneriler -> suggestions
     * - oneri_likes -> suggestion_likes
     * - oneri_comments -> suggestion_comments
     * - oneri_comment_likes -> suggestion_comment_likes
     */
    public function run(): void
    {
        // Try to disable foreign key checks (may fail on some hosts due to permissions)
        $constraintsDisabled = false;
        
        if (config('database.default') === 'sqlite') {
            try {
                DB::statement('PRAGMA foreign_keys = OFF');
                $constraintsDisabled = true;
            } catch (\Throwable $e) {
                $this->command?->warn('Could not disable SQLite foreign keys: ' . $e->getMessage());
            }
        } elseif (config('database.default') === 'pgsql') {
            // Note: session_replication_role requires superuser privileges
            // We'll skip this on shared hosting / limited permission environments
            try {
                DB::statement('SET session_replication_role = replica');
                $constraintsDisabled = true;
            } catch (\Throwable $e) {
                $this->command?->warn('Could not disable PostgreSQL constraints (requires superuser). Continuing anyway...');
            }
        }

        try {
            $this->seedRoles();
            $this->seedPermissions();
            $this->seedUsers();
            $this->seedModelHasRoles();
            $this->seedCategories();
            $this->seedSuggestions();
            $this->seedSuggestionLikes();
            $this->seedSuggestionComments();
            $this->seedSuggestionCommentLikes();
            $this->createProjectHierarchy();

            $this->command?->info('PostgresDataSeeder completed successfully.');
        } finally {
            if ($constraintsDisabled) {
                if (config('database.default') === 'sqlite') {
                    try {
                        DB::statement('PRAGMA foreign_keys = ON');
                    } catch (\Throwable $e) {
                        // Ignore
                    }
                } elseif (config('database.default') === 'pgsql') {
                    try {
                        DB::statement('SET session_replication_role = DEFAULT');
                    } catch (\Throwable $e) {
                        // Ignore
                    }
                }
            }
        }
    }

    private function seedRoles(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'user', 'guard_name' => 'web', 'created_at' => '2025-09-26 10:11:50', 'updated_at' => '2025-09-26 10:11:50'],
            ['id' => 2, 'name' => 'admin', 'guard_name' => 'web', 'created_at' => '2025-09-26 10:11:50', 'updated_at' => '2025-09-26 10:11:50'],
            ['id' => 3, 'name' => 'super_admin', 'guard_name' => 'web', 'created_at' => '2025-09-26 10:11:50', 'updated_at' => '2025-09-26 10:11:50'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
        }
        $this->command?->info('Seeded roles.');
    }

    private function seedPermissions(): void
    {
        $permissions = [
            ['id' => 1, 'name' => 'view_any_user', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'view_user', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'create_user', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'update_user', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'delete_user', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'view_any_oneri', 'guard_name' => 'web'],
            ['id' => 7, 'name' => 'view_oneri', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'create_oneri', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'update_oneri', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'delete_oneri', 'guard_name' => 'web'],
            ['id' => 11, 'name' => 'view_any_category', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'view_category', 'guard_name' => 'web'],
            ['id' => 13, 'name' => 'create_category', 'guard_name' => 'web'],
            ['id' => 14, 'name' => 'update_category', 'guard_name' => 'web'],
            ['id' => 15, 'name' => 'delete_category', 'guard_name' => 'web'],
            ['id' => 16, 'name' => 'view_any_role', 'guard_name' => 'web'],
            ['id' => 17, 'name' => 'view_role', 'guard_name' => 'web'],
            ['id' => 18, 'name' => 'create_role', 'guard_name' => 'web'],
            ['id' => 19, 'name' => 'update_role', 'guard_name' => 'web'],
            ['id' => 20, 'name' => 'delete_role', 'guard_name' => 'web'],
        ];

        $now = now();
        foreach ($permissions as $permission) {
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
            DB::table('permissions')->updateOrInsert(['id' => $permission['id']], $permission);
        }
        $this->command?->info('Seeded permissions.');
    }

    private function seedUsers(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Polat KARGILI', 'email' => 'polat@visiosoft.com.tr', 'email_verified_at' => '2025-09-26 10:11:51', 'password' => '$2y$12$uShIf2EIX.Cr8Dwikvms..K6xvu9r./jSIPs2MfIwupJm/8rSPRJC', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Omega Admin', 'email' => 'omega@admin.com', 'email_verified_at' => '2025-09-26 10:11:51', 'password' => '$2y$12$.6uVHt0/.qLDKcuQ0bciBOdLKDHK5oiEkGtmnz/32aYUACiVar7GC', 'deleted_at' => '2025-09-26 12:29:11'],
            ['id' => 3, 'name' => 'Normal Admin Main', 'email' => 'normaladmin@dut.com', 'email_verified_at' => '2025-09-26 10:11:51', 'password' => '$2y$12$1sEKihdYlHDMLECNoxA8ReGegimCJ8H3.2ezzs6jtRFSUa9.S6YI6', 'deleted_at' => '2025-09-26 12:29:08'],
            ['id' => 4, 'name' => 'Cihan Topaç', 'email' => 'cihan@visiosoft.com.tr', 'email_verified_at' => '2025-09-26 12:29:07', 'password' => '$2y$12$0gOw5HXL/K.GksVHnrWnLO00Q53WqKLlkTaijT6hAO1FKO7X7SOAS', 'deleted_at' => null],
            ['id' => 5, 'name' => 'Batuhan aydın', 'email' => 'bbatuhanaydinn@gmail.com', 'email_verified_at' => '2025-09-26 12:33:05', 'password' => '$2y$12$iVBpOw0iw3bU.ppCJCba7.RQUUYnEXMkJFcVOeuZUqUiJvLvVJVzW', 'deleted_at' => null],
            ['id' => 6, 'name' => 'deneme', 'email' => 'admin@admin.com', 'email_verified_at' => '2025-09-27 09:46:16', 'password' => '$2y$12$0pRbC3.GZuxLaRjhCcOKL.rVnQNO4CpWQ2A8po8tf0VazLgNVqIfy', 'deleted_at' => '2025-09-27 09:46:53'],
            ['id' => 7, 'name' => 'deneme', 'email' => 'deneme@gmail.com', 'email_verified_at' => '2025-09-29 09:16:23', 'password' => '$2y$12$ydAPwpe.W3iTAZuFEmZuwOHxdInFwweergDCqzxpJ6/DdJK87cByu', 'deleted_at' => null],
            ['id' => 8, 'name' => 'Ömer Karabayraktar', 'email' => 'omer.kbayraktar@gmail.com', 'email_verified_at' => '2025-09-30 08:27:28', 'password' => '$2y$12$YS..MCq1Cm4pdTTbxhEoUe.8.gOaH7EdZ1WmANEHJ08Zx5.ToUDru'],
            ['id' => 9, 'name' => 'Eda Elmacı', 'email' => 'edaelmacii@gmail.com', 'email_verified_at' => '2025-09-30 14:38:43', 'password' => '$2y$12$aUpqSbLi.nM1ZF0.lX349uFw3r8LpH7AtVhf3z.2uu2LDdFmw8wkS'],
            ['id' => 10, 'name' => 'Alain Renk', 'email' => 'herbillon@me.com', 'email_verified_at' => '2025-09-30 22:17:02', 'password' => '$2y$12$Z.waG5SrpFaUDyfPaqZuvOb1CFFR0jJxrMvcfSRB9K2tilxDN7Lea'],
            ['id' => 11, 'name' => 'Yasir Tapar', 'email' => 'taparyasir@gmail.com', 'email_verified_at' => '2025-10-07 17:48:33', 'password' => '$2y$12$rPw3VU8lqUhaH3tv6AidpuxwrKUpMNr51vXul2sOPkM9isKJEseRW'],
            ['id' => 12, 'name' => 'Ahmet SAYGI', 'email' => 'ahmet_saygii@hotmail.com', 'email_verified_at' => '2025-10-08 08:48:21', 'password' => '$2y$12$yt8pGjs.Piv41C9VZdz/4.BXb/5dFm6CiQD5/06EVXk64ocdvJG5C'],
            ['id' => 13, 'name' => 'Ömer Onur', 'email' => 'omer.onur@basaksehir-livinglab.com', 'email_verified_at' => '2025-10-09 07:22:07', 'password' => '$2y$12$EzyqGf983EY7f5UK6EUsfeeQUQTMNHYm2fp9l8fUcCLR9SQV38ZYG'],
            ['id' => 14, 'name' => 'cengiz Arslan', 'email' => 'cearslan@gmail.com', 'email_verified_at' => '2025-10-09 10:43:59', 'password' => '$2y$12$mmAV99UU6kWkGd4pjcZdkOErBt.7DINJspeKqDovLyWNapmtx3Yni'],
            ['id' => 15, 'name' => 'Küçükkara', 'email' => 'kucukkara53@gmail.com', 'email_verified_at' => '2025-10-09 10:51:56', 'password' => '$2y$12$AGOm2TQhk2A4sykrRefffO5S/Plgkz46oWkya7KqoCQUTjibdqQPm'],
            ['id' => 16, 'name' => 'soner bayar', 'email' => 'soner.bayar@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:52:30', 'password' => '$2y$12$h5HKlK8apYUnnT6DTZVnBeMfSeyhcGyQHcPXJfaYBukqzV.n89piW'],
            ['id' => 17, 'name' => 'ORHAN ARSLAN', 'email' => 'orhan.arslan@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:52:35', 'password' => '$2y$12$6lB49jlJ61hd9GwVPYMrgeFFDnHlOkXN1ePp3KBhzcJ1xngBCHPia'],
            ['id' => 18, 'name' => 'Furkan Eryiğit', 'email' => 'furkaneryigit0202@gmail.com', 'email_verified_at' => '2025-10-09 10:52:53', 'password' => '$2y$12$8S.BhnXvbJanhXVTa3pI3uL5s3dRhwk7vj0ZLfHzYKiBH8Rw5t1Ky'],
            ['id' => 19, 'name' => 'sultan köseoğlu', 'email' => 'sultan.koseoglu@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:55:19', 'password' => '$2y$12$SGGN2wQ.K8kUVrONUo7VrePDdjpYFNa/r6ud0aHXftCQQEkvdIkOS'],
            ['id' => 20, 'name' => 'FURKAN GÜNCÜ', 'email' => 'furkan1334gnc@gmail.com', 'email_verified_at' => '2025-10-09 10:55:24', 'password' => '$2y$12$hFsay/cmOeI/D68OtT9UxOrGdCJXhl656ew7qJ6R5E.sCWehEJpEu'],
            ['id' => 21, 'name' => 'gülşah şentürk', 'email' => 'gulsah.senturk@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:56:13', 'password' => '$2y$12$.cfEjAr1D1DjyEmxpdDgbert.YyIObfOqAbtka/jSXlBB1mszdeha'],
            ['id' => 22, 'name' => 'Mahir Göze', 'email' => 'mahir.goze@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:56:57', 'password' => '$2y$12$TXBvbdw.Q5.X/MXF5yPp4OYf1/cBIb671//.kpGWIlFFaOyWRTMbm'],
            ['id' => 23, 'name' => 'ENES METİN', 'email' => 'enes.metin@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 10:57:40', 'password' => '$2y$12$UeyOlZhx8IdUpMGvygTVOuTUmn4Fkk.Z2a59/L/wmF2DUR8LM72MO'],
            ['id' => 24, 'name' => 'Vahit GÜNÇİÇEK', 'email' => 'vahit.guncicek@gmail.com', 'email_verified_at' => '2025-10-09 11:23:28', 'password' => '$2y$12$KcKn63P.kbdXSperZlqrKePFS0U2QFeoINvY5nKUlUBz.VLk3nKOS'],
            ['id' => 25, 'name' => 'Nazli Savul', 'email' => 'nazlipurde@gmail.com', 'email_verified_at' => '2025-10-09 12:00:29', 'password' => '$2y$12$8y7sxPIeZTANfoVZHxihlu1YqEKJ0n1LHl.0JHr2.42UvGXMvfwVe'],
            ['id' => 26, 'name' => 'Samet yardım', 'email' => 'sametyardim0234@gmail.com', 'email_verified_at' => '2025-10-09 12:44:51', 'password' => '$2y$12$oUwpDggGc33KNmZgnkIMVe4TKzpR.FEzb9M6/qo5meuWP4NvxK4nK'],
            ['id' => 27, 'name' => 'RAMAZAN BİLİR', 'email' => 'ramazan.bilir@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 12:46:18', 'password' => '$2y$12$j3xD6mFhd6UXRkVSTxCWOOlwECElUFQDieCjqswIntBL7AJIXVkb2'],
            ['id' => 28, 'name' => 'Ali Şimşek', 'email' => 'deslateralus@gmail.com', 'email_verified_at' => '2025-10-09 13:15:35', 'password' => '$2y$12$kZrzXcHyt8CUZHXzpoKXbOkoFHOXQO6pIOOsmupgaB5B9RtrEWw5K'],
            ['id' => 29, 'name' => 'Ali Öztürk', 'email' => 'ozturk.ali@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 13:15:50', 'password' => '$2y$12$/EWpHEDbAC0QgwZSJF0KzuwR7tr.S/bPxp2K/0C6L1BCa/0Qv1cFa'],
            ['id' => 30, 'name' => 'Servet YILMAZ', 'email' => 'srvtylmz58@gmail.com', 'email_verified_at' => '2025-10-09 13:16:06', 'password' => '$2y$12$mmoGoV3oP56oniJ.vnOXve4XV8UXA64iwmcWEgATPU9MfH2mRtGna'],
            ['id' => 31, 'name' => 'Fırat Can Antlı', 'email' => 'firatcan23_12@icloud.com', 'email_verified_at' => '2025-10-09 13:16:48', 'password' => '$2y$12$j6RGXCEINLR8GWHjt.EY7eWkLQHf7ZIly/8YMfL4bEPEFv3UzQOjO'],
            ['id' => 32, 'name' => 'HAKAN ÖNER', 'email' => 'oner.hakan@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 13:19:58', 'password' => '$2y$12$1WcAbzVtIPuwgG97rs2dDO.5ynFtU7Cb6uHI/41xuer2OAjoQf2NS'],
            ['id' => 33, 'name' => 'ferhat culha', 'email' => 'frhtclha58@gmail.com', 'email_verified_at' => '2025-10-09 13:23:28', 'password' => '$2y$12$gpajaoYszarILb66WD/FauaBKsbNg4i30nTj5bTn9heUNw6uy5RAO'],
            ['id' => 34, 'name' => 'EMEL CİHANBEY', 'email' => 'emelimkaarsiv@gmail.com', 'email_verified_at' => '2025-10-09 13:24:10', 'password' => '$2y$12$kZy5DMIr6lG2XgpGY2Gg8uJNmlOtOKl3BFP8HaQ./iSnAWKuAs3oG'],
            ['id' => 35, 'name' => 'Murat Karayel', 'email' => 'murat.karayel1977@hotmail.com', 'email_verified_at' => '2025-10-09 13:24:41', 'password' => '$2y$12$CBc.2B3k4HBoA0WYjSlDw.HI9Ps7ZDToLZjBdoXfe8D7aoL4AYVM.'],
            ['id' => 36, 'name' => 'ramazan akinci', 'email' => 'ramazan.akinci@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 13:26:57', 'password' => '$2y$12$A7/D8hiRM3fhZrJbR5cd/.wGRspvgOuJay8UH0irWIZnWsAfUYz9K'],
            ['id' => 37, 'name' => 'ahmet demirdağ', 'email' => 'ahmet.demirdag@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 13:28:24', 'password' => '$2y$12$Bo0o.o7j2.ddx8ngz6UOmeJ2uoczxnLctDHc3g/7lNYS7Rb3IOJU2'],
            ['id' => 38, 'name' => 'ATAKAN AYDIN', 'email' => 'atakan.aydin@basaksehir.bel.tr', 'email_verified_at' => '2025-10-09 13:30:52', 'password' => '$2y$12$ufFEbVioRhA/OSBPi.FNjeAURmQH1GXYUl8j9oBoKFgqdDHu0NpjC'],
            ['id' => 39, 'name' => 'Üzeyir Yıldırım', 'email' => 'uyildirim1@gmail.com', 'email_verified_at' => '2025-10-09 14:31:44', 'password' => '$2y$12$ODlj67znNhxfggtZoMc2BOmQzb4tZY0InHvDsc5/.2JtN0JpakZeG'],
            ['id' => 40, 'name' => 'Hadiye Selen Kaya', 'email' => 'selenkaraca@windowslive.com', 'email_verified_at' => '2025-10-09 20:18:50', 'password' => '$2y$12$9CQ3MgqUwL9SvdmXegvxh.XAvRLF..igL.U0f9oAlDzC/xy2laU6G'],
            ['id' => 41, 'name' => 'Pınar Özcanlı Baran', 'email' => 'peroran@hotmail.com', 'email_verified_at' => '2025-10-09 21:07:58', 'password' => '$2y$12$o56xMQXNReclQyvPcmjb.OqH8FAGLwe8Mvi9/xqzy0DltBXNPAgUa'],
            ['id' => 42, 'name' => 'Robamansour', 'email' => 'robamansour@yandex.com', 'email_verified_at' => '2025-10-09 21:16:49', 'password' => '$2y$12$8SmfKzxoasz3VTlXXI3sKu.yd/aEJPjm1gyKgHpdy2sHHW1Y0jXeW'],
            ['id' => 43, 'name' => 'Bekir selçuk temel', 'email' => 'bekir.temel@basaksehir.bel.tr', 'email_verified_at' => '2025-10-10 02:47:29', 'password' => '$2y$12$GzyBE1CsCSJsn07jv4IW/e1qpB0QDrENl.Dp1m60tZ4QVaie9vAV6'],
            ['id' => 44, 'name' => 'Özlem Soysal', 'email' => 'ozlemsoysal.ceo@gmail.com', 'email_verified_at' => '2025-10-10 04:54:23', 'password' => '$2y$12$ZAfe9ibV3xf4fPjhGP.11OH2BpliSAo1uUXAF/QaTXGgFyjBUw0q.'],
            ['id' => 45, 'name' => 'Hasan Hatipoğlu', 'email' => 'hasanht78@gmail.com', 'email_verified_at' => '2025-10-10 05:52:02', 'password' => '$2y$12$3HVijhPKe59DNiUSURrtX.kfLBgjlPjOYmempogYhrx2nH5ZOehai'],
            ['id' => 46, 'name' => 'Serpil Tarhan', 'email' => 'trhnsrpl@gmail.com', 'email_verified_at' => '2025-10-10 05:56:56', 'password' => '$2y$12$mnMGecPqT5sP/X9OhlHKfOizBr8ZMxzOCwNp8R3BDXrRD6/51Othy'],
            ['id' => 47, 'name' => 'Müberra SAĞINDIK', 'email' => 'muberrasagindik@gmail.com', 'email_verified_at' => '2025-10-10 10:39:13', 'password' => '$2y$12$QQk9TDXJhb8C1UhiA7w9Luvx1nzMvRGIYOqtp1IdET118RLgz766G'],
            ['id' => 48, 'name' => 'Kevser Savaş', 'email' => 'imran2009fatih2013@gmail.com', 'email_verified_at' => '2025-10-10 21:19:50', 'password' => '$2y$12$hjTT4cGYPdwlLYbmjoekgurdvGcDALCeqxZwlDCaJn38cDJiPJNWC'],
            ['id' => 49, 'name' => 'İbrahim Karakuzu', 'email' => 'ibrahimkarakuzu@gmail.com', 'email_verified_at' => '2025-10-11 14:21:42', 'password' => '$2y$12$BKhMG0LzgBJ1wP6NRpBjpOZtJPRyD2MsnrCRZQ1tOEDPTLpNuQOX2'],
            ['id' => 50, 'name' => 'Yusuf tellioğlu', 'email' => 'yusufoztel@gmail.com', 'email_verified_at' => '2025-10-11 16:44:55', 'password' => '$2y$12$bsrKGIZCmkCKV8tKXsv8yOo6RnRx.4otHNgtYr/VxNtGesrXvWsqW'],
            ['id' => 51, 'name' => 'Gülseren Şahin', 'email' => 'gulserensahin0552@gmail.com', 'email_verified_at' => '2025-10-13 09:33:00', 'password' => '$2y$12$UWMMQSaY9a5YS2UcwKUgIuWAVZ1RhhkUcgJ0zDsvHavewLZVKPqHe'],
            ['id' => 52, 'name' => 'Fatma Akköse', 'email' => 'fatmaakkose94@gmail.com', 'email_verified_at' => '2025-10-13 11:28:40', 'password' => '$2y$12$/.xEqt1J56BMwSCtDgMtE.eBKUOkGmAQceNJI7CbFhj0XqxCZL6MO'],
            ['id' => 53, 'name' => 'Hüseyin Karabulut', 'email' => 'hsynq@hotmail.com', 'email_verified_at' => '2025-10-16 16:53:32', 'password' => '$2y$12$O1tUqm7D00JnChJ9mu3uJOf2fkk5JLUm4dRx6tEo9HroIotqC4YRK'],
            ['id' => 54, 'name' => 'Nuriye Sezer Okur', 'email' => 'nuriyesezer@hotmail.com', 'email_verified_at' => '2025-10-16 21:52:52', 'password' => '$2y$12$EnKv1cvcc2OuPmdSWfuP8Oaw5HQDnP2SJbGZfB2Da7NMd/J1s0G0u'],
            ['id' => 55, 'name' => 'Elifnur Sekman', 'email' => 'sekmanelifnur@gmail.com', 'email_verified_at' => '2025-10-16 22:02:53', 'password' => '$2y$12$.0y7TYx32WK9Bsh6RjPDFuAC5TbQNJ7J2Tc9F2UnURfennp6XtSpi'],
            ['id' => 56, 'name' => 'Meral Alkan', 'email' => 'meralalkan81@hotmail.com', 'email_verified_at' => '2025-10-16 23:01:10', 'password' => '$2y$12$bjmAo/0gIMH9ZS/.YievguLcX.KeMSNJNYKr43aMssmHKwT2KLiGu'],
            ['id' => 57, 'name' => 'Eylül Güngör', 'email' => 'hello.eylulg@gmail.com', 'email_verified_at' => '2025-10-17 11:37:05', 'password' => '$2y$12$pnCt173ti79D/XMGWbSjFebH3JahOhT.gTb2XeDYYhYpgnmWvs6l.'],
            ['id' => 58, 'name' => 'ÖMER ASLAN', 'email' => 'omer_aslan_92@outlook.com', 'email_verified_at' => '2025-10-17 14:29:41', 'password' => '$2y$12$Po.Us/U0SbLc3ESZcWXx9.FBA5hAiYtivj8b1oAqrB8nNqRXSPDia'],
            ['id' => 59, 'name' => 'Ahmet Miraç Berber', 'email' => 'ahmet28mrc@gmail.com', 'email_verified_at' => '2025-10-18 08:20:45', 'password' => '$2y$12$h7G0oNzSbVekkdaiwv3A4O97x3JZkapJQ4ETAPG28YuZXZ8Hoc52q'],
            ['id' => 60, 'name' => 'Laith alsalamah', 'email' => 'laithalsalamah2020@gmail.com', 'email_verified_at' => '2025-10-18 09:05:36', 'password' => '$2y$12$Fgf036xidk.9.c7XIMXZUOSJC.AKWObkRIvtkzvWc40U6sWWzNY3q'],
            ['id' => 61, 'name' => 'Büşra gürbüz', 'email' => 'busragurbuz034@gmail.com', 'email_verified_at' => '2025-10-19 09:28:47', 'password' => '$2y$12$.gunPClDhr4/MDwpqmT48ur3y/GIpjL8GV0S72mP.uOOAIOQelVNu'],
            ['id' => 62, 'name' => 'korkmaz çakar', 'email' => 'korkmazcakar02@gmail.com', 'email_verified_at' => '2025-10-19 11:04:41', 'password' => '$2y$12$YRG0KDodNE14mtG9Zfm4x.8L/jyj73DokY6rVU8z2kWs4TGzsLVUy'],
            ['id' => 63, 'name' => 'Alanur yaman', 'email' => 'yamanalanur34@gmail.com', 'email_verified_at' => '2025-11-04 16:29:15', 'password' => '$2y$12$7qPa.7xf7UdstMcM0VxMSuiozq.7MN83fbq6s2ZsVr6yazg4vO/m2'],
            ['id' => 64, 'name' => 'Ömer kazan', 'email' => 'omrkzn@gmail.com', 'email_verified_at' => '2025-11-08 20:12:42', 'password' => '$2y$12$W3z9bBHLazvyZdQS0boI2OS1FjJ5m5hF5CzCDP13pP1lcff3toJB.'],
        ];

        $now = now();
        foreach ($users as $user) {
            $user['created_at'] = $user['created_at'] ?? $now;
            $user['updated_at'] = $user['updated_at'] ?? $now;
            DB::table('users')->updateOrInsert(['id' => $user['id']], $user);
        }
        $this->command?->info('Seeded ' . count($users) . ' users.');
    }

    private function seedModelHasRoles(): void
    {
        // User 1 (Polat) - super_admin, admin, user
        $roleAssignments = [
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 1],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 1],
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 1],
            // User 4 (Cihan) - super_admin, admin, user
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            // User 5 (Batuhan) - admin, user
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            // User 8 (Ömer) - admin, user  
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 8],
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 8],
            // User 9 (Eda) - admin, user
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 9],
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 9],
        ];

        // All other users get 'user' role
        for ($i = 7; $i <= 64; $i++) {
            if (!in_array($i, [8, 9])) { // Skip already assigned admins
                $roleAssignments[] = ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => $i];
            }
        }

        foreach ($roleAssignments as $assignment) {
            DB::table('model_has_roles')->updateOrInsert($assignment, $assignment);
        }
        $this->command?->info('Seeded model_has_roles.');
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'id' => 2,
                'name' => 'Kayabaşı Meydan Projesi',
                'description' => 'Kayabaşı Meydanı\'nın güzelleştirilmesine yönelik bazı fikirler...',
                'district' => 'Başakşehir',
                'neighborhood' => 'Kayabaşı',
                'country' => 'Türkiye',
                'province' => 'İstanbul',
                'created_at' => '2025-09-28 10:16:54',
                'updated_at' => '2025-10-09 07:17:00',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(['id' => $category['id']], $category);
        }
        $this->command?->info('Seeded categories.');
    }

    private function seedSuggestions(): void
    {
        // Legacy oneriler -> suggestions (project_id NULL = project, project_id NOT NULL = suggestion)
        $suggestions = [
            [
                'id' => 2,
                'category_id' => 2,
                'created_by_id' => null,
                'updated_by_id' => 1,
                'title' => 'Çocuk Konseptli Rekreasyon Alanı',
                'description' => 'Çocuk oyun alanları ve oturma-dinlenme alanları rekreasyon alanı',
                'city' => 'İstanbul',
                'status' => 'active',
                'created_at' => '2025-09-28 10:20:55',
                'updated_at' => '2025-09-29 09:22:40',
            ],
            [
                'id' => 3,
                'category_id' => 2,
                'created_by_id' => null,
                'updated_by_id' => 1,
                'title' => 'Eco-Meydan',
                'description' => 'oyun alanları, peyzaj alanları, bisiklet yolu ve parkı ve yeşil sistem konularak mekanın aktifleştirilmesi, cazibe kazandırılması, skooter, bisiklet gibi ulaşım araçlarıyla carbon emisyonunun azaltılması için depozito karşılığında taşıma aracı(vagon) kiralanması',
                'city' => 'İstanbul',
                'status' => 'active',
                'created_at' => '2025-09-28 10:22:38',
                'updated_at' => '2025-09-29 07:16:29',
            ],
            [
                'id' => 4,
                'category_id' => 2,
                'created_by_id' => null,
                'updated_by_id' => 1,
                'title' => 'Gençlere Yönelik Rekreasyon Alanı',
                'description' => 'Gençlerin doğadan ve spordan kopmadan hem dinlenebilicekleri hem sosyalleşebilecekleri hemde eğlenebilicekleri güvenli bir alan oluşturmak amacıyla tasarlanmıştır.',
                'city' => 'İstanbul',
                'status' => 'active',
                'created_at' => '2025-09-28 10:25:47',
                'updated_at' => '2025-10-01 10:20:03',
            ],
        ];

        foreach ($suggestions as $suggestion) {
            DB::table('suggestions')->updateOrInsert(['id' => $suggestion['id']], $suggestion);
        }
        $this->command?->info('Seeded suggestions (oneriler).');
    }

    private function seedSuggestionLikes(): void
    {
        // Legacy oneri_likes -> suggestion_likes (oneri_id -> suggestion_id)
        $likes = [
            ['id' => 23, 'user_id' => 9, 'suggestion_id' => 2, 'created_at' => '2025-09-30 14:40:03'],
            ['id' => 28, 'user_id' => 8, 'suggestion_id' => 3, 'created_at' => '2025-10-09 07:21:18'],
            ['id' => 35, 'user_id' => 14, 'suggestion_id' => 2, 'created_at' => '2025-10-09 10:45:48'],
            ['id' => 36, 'user_id' => 17, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:52:45'],
            ['id' => 40, 'user_id' => 16, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:53:51'],
            ['id' => 49, 'user_id' => 15, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:54:41'],
            ['id' => 50, 'user_id' => 18, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:55:37'],
            ['id' => 51, 'user_id' => 19, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:56:28'],
            ['id' => 52, 'user_id' => 21, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:57:08'],
            ['id' => 53, 'user_id' => 22, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:57:13'],
            ['id' => 56, 'user_id' => 23, 'suggestion_id' => 3, 'created_at' => '2025-10-09 10:58:06'],
            ['id' => 57, 'user_id' => 20, 'suggestion_id' => 2, 'created_at' => '2025-10-09 10:58:32'],
            ['id' => 58, 'user_id' => 24, 'suggestion_id' => 3, 'created_at' => '2025-10-09 11:24:45'],
            ['id' => 59, 'user_id' => 25, 'suggestion_id' => 3, 'created_at' => '2025-10-09 12:01:45'],
            ['id' => 60, 'user_id' => 26, 'suggestion_id' => 3, 'created_at' => '2025-10-09 12:45:16'],
            ['id' => 63, 'user_id' => 27, 'suggestion_id' => 3, 'created_at' => '2025-10-09 12:46:56'],
            ['id' => 67, 'user_id' => 30, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:16:59'],
            ['id' => 70, 'user_id' => 29, 'suggestion_id' => 4, 'created_at' => '2025-10-09 13:18:56'],
            ['id' => 79, 'user_id' => 32, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:22:26'],
            ['id' => 80, 'user_id' => 31, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:22:41'],
            ['id' => 84, 'user_id' => 33, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:25:03'],
            ['id' => 85, 'user_id' => 35, 'suggestion_id' => 4, 'created_at' => '2025-10-09 13:25:19'],
            ['id' => 86, 'user_id' => 34, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:27:01'],
            ['id' => 87, 'user_id' => 36, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:27:11'],
            ['id' => 88, 'user_id' => 37, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:28:48'],
            ['id' => 91, 'user_id' => 38, 'suggestion_id' => 3, 'created_at' => '2025-10-09 13:31:52'],
            ['id' => 93, 'user_id' => 40, 'suggestion_id' => 4, 'created_at' => '2025-10-09 20:19:57'],
            ['id' => 96, 'user_id' => 42, 'suggestion_id' => 4, 'created_at' => '2025-10-09 21:17:26'],
            ['id' => 100, 'user_id' => 44, 'suggestion_id' => 3, 'created_at' => '2025-10-10 04:56:03'],
            ['id' => 108, 'user_id' => 1, 'suggestion_id' => 3, 'created_at' => '2025-10-10 07:45:34'],
            ['id' => 110, 'user_id' => 43, 'suggestion_id' => 3, 'created_at' => '2025-10-10 07:46:59'],
            ['id' => 113, 'user_id' => 47, 'suggestion_id' => 4, 'created_at' => '2025-10-10 10:40:09'],
            ['id' => 115, 'user_id' => 49, 'suggestion_id' => 3, 'created_at' => '2025-10-11 14:23:48'],
        ];

        foreach ($likes as $like) {
            $like['updated_at'] = $like['created_at'];
            DB::table('suggestion_likes')->updateOrInsert(['id' => $like['id']], $like);
        }
        $this->command?->info('Seeded ' . count($likes) . ' suggestion_likes.');
    }

    private function seedSuggestionComments(): void
    {
        // Legacy oneri_comments -> suggestion_comments (oneri_id -> suggestion_id)
        $comments = [
            ['id' => 1, 'suggestion_id' => 4, 'user_id' => 8, 'comment' => 'Paten ve kaykay pistleri tehlikeli olabilir', 'is_approved' => true, 'parent_id' => null, 'created_at' => '2025-09-30 08:28:30', 'updated_at' => '2025-10-03 20:45:59'],
            ['id' => 2, 'suggestion_id' => 3, 'user_id' => 8, 'comment' => 'Mavi gölet olsa iyi olur', 'is_approved' => true, 'parent_id' => null, 'created_at' => '2025-10-09 07:17:53', 'updated_at' => '2025-10-10 06:49:52'],
            ['id' => 3, 'suggestion_id' => 4, 'user_id' => 29, 'comment' => 'Kaykay pisti fikri özellikle gençlere hitap eden, eksikliğini hissettiğimiz harika bir yenilik. Görseldeki gibi modern ve güvenli bir alanda gençlerin hem spor yapıp enerjilerini atabileceği hem de sosyal bir ortam bulabileceği düşünülmüş. Projenin açıklamasında belirtildiği gibi \'\'doğadan ve spordan kopmadan\'\' bir alan yaratılması vizyonu çok yerinde. gençlerin buluşma noktası olur!', 'is_approved' => true, 'parent_id' => null, 'created_at' => '2025-10-09 13:24:28', 'updated_at' => '2025-10-10 06:49:31'],
            ['id' => 4, 'suggestion_id' => 2, 'user_id' => 43, 'comment' => 'Meydana önemli etkinliklerin izlenmesi adına dev Led ekran koyulabilir', 'is_approved' => true, 'parent_id' => null, 'created_at' => '2025-10-10 02:50:46', 'updated_at' => '2025-10-10 06:49:42'],
            ['id' => 5, 'suggestion_id' => 3, 'user_id' => 60, 'comment' => 'Alan devasa bir alan değil sonuçta bence bikaç şey olsun ama hakkıyla yapılmış duyarsız vatandaşlar tarafından zamanla tahrip edilemiycek şekilde tasarlanmış bir alan olsun (Fotoğraftaki satranç alanı mesela orası eminimki birkaç ay sonra ya parçalanmış ya taşları eksik halde olucak)', 'is_approved' => false, 'parent_id' => null, 'created_at' => '2025-10-18 09:09:30', 'updated_at' => '2025-10-18 09:09:30'],
        ];

        foreach ($comments as $comment) {
            DB::table('suggestion_comments')->updateOrInsert(['id' => $comment['id']], $comment);
        }
        $this->command?->info('Seeded ' . count($comments) . ' suggestion_comments.');
    }

    private function seedSuggestionCommentLikes(): void
    {
        // Legacy oneri_comment_likes -> suggestion_comment_likes
        $likes = [
            ['id' => 2, 'suggestion_comment_id' => 1, 'user_id' => 1, 'created_at' => '2025-10-06 11:27:34'],
            ['id' => 3, 'suggestion_comment_id' => 1, 'user_id' => 14, 'created_at' => '2025-10-09 10:44:44'],
            ['id' => 5, 'suggestion_comment_id' => 1, 'user_id' => 21, 'created_at' => '2025-10-09 10:59:46'],
            ['id' => 6, 'suggestion_comment_id' => 1, 'user_id' => 43, 'created_at' => '2025-10-10 02:48:59'],
            ['id' => 7, 'suggestion_comment_id' => 2, 'user_id' => 1, 'created_at' => '2025-10-10 07:15:20'],
            ['id' => 8, 'suggestion_comment_id' => 3, 'user_id' => 60, 'created_at' => '2025-10-18 09:10:51'],
            ['id' => 9, 'suggestion_comment_id' => 4, 'user_id' => 60, 'created_at' => '2025-10-18 09:11:17'],
        ];

        foreach ($likes as $like) {
            $like['updated_at'] = $like['created_at'];
            DB::table('suggestion_comment_likes')->updateOrInsert(['id' => $like['id']], $like);
        }
        $this->command?->info('Seeded ' . count($likes) . ' suggestion_comment_likes.');
    }

    private function createProjectHierarchy(): void
    {
        // Create main "Projeler" category (top-level, no parent)
        $mainCategory = DB::table('categories')->where('name', 'Projeler')->first();
        if (!$mainCategory) {
            $mainCategoryId = DB::table('categories')->insertGetId([
                'name' => 'Projeler',
                'description' => 'Tüm projeler',
                'parent_id' => null, // En üst kategori - parent yok
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $mainCategoryId = $mainCategory->id;
            // Ensure parent_id is null for existing category
            DB::table('categories')->where('id', $mainCategoryId)->update(['parent_id' => null]);
        }

        // Create project group for Kayabaşı
        $projectGroup = DB::table('project_groups')->where('name', 'Kayabaşı Meydan Projesi')->first();
        if (!$projectGroup) {
            $projectGroupId = DB::table('project_groups')->insertGetId([
                'name' => 'Kayabaşı Meydan Projesi',
                'category_id' => $mainCategoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $projectGroupId = $projectGroup->id;
        }

        // Create the main project (project_id = NULL means it's a project)
        $project = DB::table('suggestions')
            ->where('title', 'Kayabaşı Meydan Projesi')
            ->whereNull('project_id')
            ->first();

        if (!$project) {
            $projectId = DB::table('suggestions')->insertGetId([
                'category_id' => $mainCategoryId,
                'project_id' => null,
                'title' => 'Kayabaşı Meydan Projesi',
                'description' => 'Kayabaşı Meydanı\'nın güzelleştirilmesine yönelik bazı fikirler...',
                'status' => 'active',
                'city' => 'İstanbul',
                'district' => 'Başakşehir',
                'neighborhood' => 'Kayabaşı',
                'created_at' => '2025-09-28 10:16:54',
                'updated_at' => now(),
            ]);
        } else {
            $projectId = $project->id;
        }

        // Link project to project group
        DB::table('project_group_suggestion')->updateOrInsert(
            ['project_group_id' => $projectGroupId, 'suggestion_id' => $projectId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Update all suggestions to link to this project
        DB::table('suggestions')
            ->where('category_id', 2)
            ->whereNull('project_id')
            ->where('id', '!=', $projectId)
            ->update([
                'project_id' => $projectId,
                'category_id' => $mainCategoryId,
            ]);

        $this->command?->info('Created project hierarchy.');
    }
}
