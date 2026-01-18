# Proje Gelişme Raporu (ilk commit → bugün)

Bu rapor, 20.08.2025 tarihli ilk commmitten 09.01.2026 tarihli otomatik çeviri geliştirmesine kadar **git geçmişi** temel alınarak hazırlanmıştır. Özet, kilometre taşları ve mevcut durum bilgisi içerir.

## Kısa Özet
- Laravel tabanlı temel iskelet kuruldu; medya ve kategori yönetimi eklendi.
- Filament üzerinden kategori/proje kaynakları, statü yapısı ve detaylı filtreleme olgunlaştırıldı.
- Sürükle-bırak tabanlı tasarım aracı ve görsel yükleme deneyimi iyileştirildi; sonrasında öneri tarafında gereksiz tasarım parçaları temizlendi.
- Kullanıcı paneli, beğeni/yorum, dinamik arka planlar ve responsive tasarım iyileştirmeleri yapıldı.
- Rol bazlı yetkilendirme, çok dilli arayüz (TR/EN + FR/DE/SV), dil seçiciler ve yerelleştirme dosyaları tamamlandı.
- Konum hiyerarşisi (ülke→şehir→ilçe→mahalle), güçlü filtreleme ve arama yetenekleri eklendi; CSV destekli içe aktarma geliştirildi.
- Bildirim merkezi, zamanlanmış bildirimler, anket/survey yanıt yönetimi, demografik geri bildirim ve oylama kapandı bildirimleri devreye alındı.
- Proje karar süreçleri (en çok oy alan, belediye tercihi, hibrit yeni öneri) ve otomatik çeviri sistemi eklendi.

## Kronolojik Kilometre Taşları
- **20-21.08.2025** – İlk Laravel iskeleti, medya tablosu, kategori yapısı, proje statü enum’u ve Filament kategori/proje kaynakları (389d8b5, 051cd06, 59a37c2, 954dfec).
- **22.08.2025** – Detaylı proje filtreleri, ProjectObserver, görsel yükleme iyileştirmeleri ve sürükle-bırak tasarım aracıyla ilk tasarım deneyimi (875a94f, c1418a7, a6ba00d, dc1a71d).
- **25-26.08.2025** – Soft delete, tekil görsel yükleme, ProjectDesignElement modeli/migrasyonu ve tasarım veri görüntüleyici (cf7f9ba, 54d7d5d, 0ff25d4, aa0333d).
- **02-03.09.2025** – Polat dalı birleşmeleri, seed/göç dosyası temizlikleri ve kullanıcı/izin refaktörleri (ed6b610, 7c9cece, 7fa6826).
- **18-20.09.2025** – Tasarım fonksiyonunun önerilerden temizlenmesi, kapsamlı testler, responsive proje/öneri detay sayfaları, AJAX beğeni ve dinamik arka planlar (92280cb, bbcc5c3, ab76083, 46c2e1f, 43a0889).
- **30.09-09.10.2025** – Dev4 güncellemeleri, dil seçici ve İngilizce çevirilerle ilk çok dillilik adımı (fa05fb1, 146c628).
- **08-09.11.2025** – Kod tabanının İngilizceleştirilmesi, ProjectGroup hiyerarşisi, bütçe aralıkları ve proje-öneri ilişki düzeni (61b5564, e3b9903, 0971aed, de7cfc1).
- **14.11.2025** – Kategori hiyerarşisi, kapsamlı filtreler, bütçe modülü, bildirim sistemi ve yorum testleri (0e8de0e, 5751c33, 2263f3d, 6363f9d, 99767a5).
- **24-25.11.2025** – Arama, konum yönetimi (ülke/şehir/ilçe/mahalle), CSV destekli lokasyon içe aktarımı ve geliştirilmiş çözümleyici (49f2b2a, 6a2ec51, 56f0c7d, fb273e3, 7269abd).
- **25.11-01.12.2025** – Ortak tablo aksiyonları/refaktörler, dosya yükleme sınırlarının artırılması, konum filtreleme ve öneri yorum kaynağı ile dashboard widget’ları (55658b1, f6c0257, 3d5aff7, 8d332c7, 03ae507, 9cda320).
- **08-10.12.2025** – Kullanıcı giriş/kayıt arayüzleri, kategori temelli grup başlıkları, Postgres seeder ve sağlamlaştırılmış ID/sequence düzenleri (f3db62b, 0503fd5, 9ce668a, 017c219, 0f6d4c3).
- **24-27.12.2025** – Demografik geri bildirim toplama, beğeni grafikleri, survey analitikleri, oy kapanış bildirimleri, RoleResource ve karar değerlendirme/sticky filtre deneyimi (5a1fd2c, 149ba01, cc7601c, fc45725, e9e7ad9, a726327, 39d0867).
- **28.12.2025-05.01.2026** – Kullanıcı sayfa düzeni iyileştirmeleri, “tüm önerileri gör” bağlantıları ve Fransızca/Almanca/İsveççe dil desteği (83e2f58, 77a5f1b, dc2f77f).
- **07-08.01.2026** – Proje kararının finalize edilmesi (en çok oylanan/belediye/hibrit), birleşik Location modeliyle arama/filtre refaktörü ve migration adı düzeltmesi (ad427ac, d90c5fc, d24d13f).
- **09.01.2026** – Dinamik içerik için otomatik çeviri sistemi (e18edde).

## Bugünkü Durum (Özet)
- **Yönetim:** Filament tabanlı kaynaklar; rol bazlı yetki ve çok dilli arayüz.
- **İçerik:** Proje/öneri ilişkileri, konum ve bütçe filtreleri, detaylı arama.
- **Kullanıcı Deneyimi:** Beğeni/yorum, demografik geri bildirim, sticky filtreler, dinamik arka planlar.
- **İş Süreçleri:** Bildirim merkezi, oylama kapanış/karar mekanizmaları, survey/analitikler.
- **Altyapı:** Gelişmiş seeder’lar, yüksek dosya yükleme limitleri, otomatik çeviri.
