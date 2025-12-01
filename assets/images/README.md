# Author Image Customization

The author block pattern uses a custom image that can be easily replaced per author.

## How to Change the Author Image

### Option 1: Replace the Placeholder (Simplest)

1. Add your author image to `/assets/images/` folder
2. Name it `author-placeholder.jpg` (or update the pattern to match your filename)
3. Recommended size: 150x150 pixels minimum, square aspect ratio
4. The image will be displayed as a circle (150px diameter)

### Option 2: Edit the Pattern File

1. Open `/patterns/author-with-donation.php`
2. Find the line with the image source:
   ```php
   <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/author-placeholder.jpg"
   ```
3. You can:
   - Change the image filename
   - Use a custom field to set different images per author
   - Use WordPress user meta to pull author-specific images

### Option 3: Use WordPress Custom Fields (Advanced)

You can modify the pattern to pull images from custom fields:

```php
<?php
$author_image = get_user_meta(get_the_author_meta('ID'), 'custom_author_image', true);
$image_url = $author_image ? $author_image : get_template_directory_uri() . '/assets/images/author-placeholder.jpg';
?>
<img src="<?php echo esc_url($image_url); ?>" alt="<?php the_author(); ?>" style="border-radius:50%;object-fit:cover;width:150px;height:150px"/>
```

## Pattern Location

- Pattern file: `/patterns/author-with-donation.php`
- Used in: `/templates/single.html`

## What's Included

The author block pattern includes:

- Circular author image (150x150px)
- "About the Author" heading
- Author name (linked to author archive)
- Author biography
- TM Donate donation block

## Customization Tips

- To change the image size, update both the width/height in the style attribute and the `flex-basis` percentage in the column
- To use gravatar instead, replace the image block with `<!-- wp:post-author-avatar /-->`
- The pattern automatically pulls the author name and bio from WordPress user profile
