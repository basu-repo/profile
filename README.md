# My Professional Website

A modern, responsive website template ready for cPanel deployment.

## 📁 Project Structure

```
my_profile/
├── index.html           # Home page
├── about.html          # About page
├── contact.html        # Contact page
├── css/
│   └── styles.css      # Main stylesheet
├── js/
│   └── script.js       # JavaScript functionality
├── images/             # Folder for images (create as needed)
└── README.md           # This file
```

## 🚀 Getting Started

### Local Development

1. Open any of the HTML files in your web browser to view the website
2. Edit the HTML files to customize your content
3. Modify `css/styles.css` for styling changes
4. Update `js/script.js` for interactive features

### File Descriptions

- **index.html** - Main landing page with hero section and features
- **about.html** - About page to tell your story
- **contact.html** - Contact page with contact form
- **css/styles.css** - All website styling and responsive design
- **js/script.js** - Form validation, smooth scrolling, and interactivity

## 📦 Customization Guide

### Update Site Information

1. **Website Title**: Edit the `<title>` tags in each HTML file
2. **Navigation Links**: Modify the navigation menu items
3. **Content**: Update text in each section
4. **Colors**: Edit color values in `css/styles.css`
5. **Contact Info**: Update email, phone, and location in `contact.html`

### Add Your Logo

Replace "MyWebsite" in the `.logo` div with your company/personal brand name or add an image:
```html
<div class="logo">
    <img src="images/logo.png" alt="Logo">
</div>
```

### Add Images

1. Create an `images/` folder
2. Place your images there
3. Reference them in HTML:
```html
<img src="images/your-image.jpg" alt="Description">
```

## 💾 Deploy to cPanel

### Method 1: Using cPanel File Manager (Recommended)

1. **Connect to cPanel** at `yourdomain.com:2083`
2. **Open File Manager**
3. **Navigate** to `public_html` folder
4. **Upload** all files and folders from this project:
   - Upload `index.html`, `about.html`, `contact.html`
   - Upload the entire `css/` folder
   - Upload the entire `js/` folder
   - Create and upload `images/` folder

### Method 2: Using FTP

1. **Download an FTP client** (FileZilla, WinSCP, etc.)
2. **Connect** using FTP credentials from cPanel
3. **Navigate** to `public_html` folder
4. **Upload** all files and folders to `public_html`

### Method 3: Using SSH (Advanced)

```bash
# Connect via SSH
ssh username@yourdomain.com

# Navigate to public_html
cd public_html

# Upload files (from your local machine using SCP)
scp -r /path/to/my_profile/* username@yourdomain.com:~/public_html/
```

## ✅ Post-Deployment Checklist

- [ ] Visit `yourdomain.com` to verify website loads
- [ ] Check all navigation links work
- [ ] Test contact form functionality
- [ ] View on mobile devices (responsive design)
- [ ] Check all images load correctly
- [ ] Verify footer and links display properly

## 🔧 Important Notes for cPanel

1. **Main Page**: The file `index.html` is automatically recognized as the home page
2. **URL Structure**: Files are accessed as:
   - `yourdomain.com/` → index.html
   - `yourdomain.com/about.html` → about.html
   - `yourdomain.com/contact.html` → contact.html
3. **Directory Names**: Keep folder names lowercase without spaces
4. **File Permissions**: Most hosting providers set correct permissions automatically

## 📧 Contact Form Setup

### Option 1: Basic Client-Side Validation (Current)
The form currently validates on the client side and shows a success message.

### Option 2: Server-Side Processing
To actually send emails, you'll need a backend script. Create a PHP file (`submit-form.php`):

```php
<?php
// submit-form.php
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

$to = 'your-email@example.com';
$subject = 'New Contact Form Submission';
$body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

if (mail($to, $subject, $body)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
```

Then uncomment the JavaScript function in `js/script.js` to send data to PHP.

## 🎨 Customization Tips

- **Colors**: Edit the color codes in `css/styles.css` (look for hex colors like `#2c3e50`)
- **Fonts**: Change `font-family` in the body CSS rule
- **Layout**: Modify grid columns in `.feature-grid`
- **Spacing**: Adjust padding and margin values

## 📱 Responsive Breakpoints

The website is optimized for:
- Desktop (1200px and above)
- Tablet (768px - 1199px)
- Mobile (480px - 767px)
- Small Mobile (below 480px)

## 🆘 Troubleshooting

| Issue | Solution |
|-------|----------|
| Page not loading | Check file permissions (644 for files, 755 for folders) |
| Images not showing | Verify path is correct (use relative paths like `images/pic.jpg`) |
| Styles not applied | Clear browser cache (Ctrl+Shift+Del or Cmd+Shift+Del) |
| Links not working | Ensure HTML files are in `public_html` root |
| Form not working | Check if your hosting supports PHP, or use client-side validation |

## 📚 Resources

- [cPanel Documentation](https://documentation.cpanel.net/)
- [HTML Reference](https://developer.mozilla.org/en-US/docs/Web/HTML)
- [CSS Reference](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [JavaScript Reference](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

## 📄 License

Free to use and modify for your own website.

---

**Happy building! 🎉** If you have questions, check the troubleshooting section or consult your hosting provider.
