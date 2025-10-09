# Translation Guide - DUT Vote Platform

## Overview
All Turkish text in the user panel has been translated to English using Laravel's built-in localization system. The translations support switching between Turkish and English languages.

## Translation Files Location
- **English**: `lang/en/user.php`
- **Turkish**: `lang/tr/user.php`

## How to Switch Languages

### Method 1: Environment Variable
Edit your `.env` file and set:
```env
APP_LOCALE=en  # For English
APP_LOCALE=tr  # For Turkish
```

### Method 2: Runtime (in Controllers)
```php
App::setLocale('en'); // Switch to English
App::setLocale('tr'); // Switch to Turkish
```

## Translation Coverage

### Views Translated:
1. ✅ `resources/views/user/layout.blade.php` - Navigation and Footer
2. ✅ `resources/views/user/index.blade.php` - Home Page
3. ✅ `resources/views/user/projects.blade.php` - Projects List
4. ✅ `resources/views/user/project-suggestions.blade.php` - Project Suggestions
5. ✅ `resources/views/user/suggestion-detail.blade.php` - Suggestion Detail Page
6. ✅ `resources/views/user/auth/login.blade.php` - Login Page
7. ✅ `resources/views/user/auth/register.blade.php` - Register Page

### Translation Categories:
- Navigation elements (Home, Projects, Login, Register, Logout)
- Hero sections and descriptions
- Project information and statistics
- Voting system messages
- Comments and replies
- Authentication forms
- Footer content
- Error and success messages
- JavaScript alert messages

## Example Translations

### Navigation
| Turkish | English |
|---------|---------|
| Ana Sayfa | Home |
| Projeler | Projects |
| Giriş | Login |
| Kayıt | Register |
| Çıkış | Logout |

### Common Terms
| Turkish | English |
|---------|---------|
| Öneri | Suggestion |
| Beğeni | Like |
| Yorum | Comment |
| Proje | Project |
| Oylama Sistemi | Voting System |
| Toplam | Total |

### Messages
| Turkish | English |
|---------|---------|
| Beğeni yapmak için giriş yapmanız gerekiyor | You must login to like |
| Öneri beğenildi! | Suggestion liked! |
| Yorumunuz başarıyla gönderildi | Your comment has been successfully sent |

## Usage in Blade Templates

### Basic Usage
```blade
{{ __('user.home') }}
{{ __('user.projects') }}
{{ __('user.login') }}
```

### With Parameters
```blade
{{ __('user.comment_pending_approval', ['type' => 'comment']) }}
{{ __('user.reply_to_user_placeholder', ['name' => $user->name]) }}
```

### In JavaScript (using Blade)
```blade
<script>
    showMessage('{{ __('user.must_login_to_like') }}', 'error');
    alert('{{ __('user.comment_sent_success') }}');
</script>
```

## Adding New Translations

To add a new translation:

1. Add the key to both `lang/en/user.php` and `lang/tr/user.php`
2. Use the translation in your Blade template with `{{ __('user.your_key') }}`

Example:
```php
// lang/en/user.php
'your_new_key' => 'Your English Text',

// lang/tr/user.php
'your_new_key' => 'Türkçe Metniniz',
```

```blade
<!-- In your Blade template -->
{{ __('user.your_new_key') }}
```

## Notes
- The default locale is now set to English ('en')
- Turkish is configured as the fallback locale
- All JavaScript messages in user-facing pages have been translated
- Admin panel translations are separate and use Filament's translation system
