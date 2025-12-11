const mix = require('laravel-mix');

// Настройка для SCSS и JS
mix.setPublicPath('public')
    .setResourceRoot('../') // для корректных путей к шрифтам/картинкам

    // Компиляция SCSS
    .sass('resources/scss/styles.scss', 'public/css')
    
    // Компиляция JS
    .js('resources/js/index.js', 'public/js')
    
    // Копирование Bootstrap шрифтов (если нужны)
    //.copy('node_modules/bootstrap/dist/fonts', 'public/fonts')
    
    // Настройки PostCSS для автопрефиксов
    .options({
        postCss: [
            require('autoprefixer')
        ],
        processCssUrls: false // чтобы не ломались относительные пути
    });

// Версионность для production
if (mix.inProduction()) {
    mix.version();
}

// Source maps для разработки
if (!mix.inProduction()) {
    mix.sourceMaps();
}