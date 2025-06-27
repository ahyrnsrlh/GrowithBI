# GrowithBI

Modern web application built with Laravel, Inertia.js, and Vue.js for internship management system at Bank Indonesia KPW Lampung.

## Tech Stack

-   **Backend**: Laravel 11
-   **Database**: MySQL 8.0 (growithbi)
-   **Frontend**: Vue.js 3
-   **SPA Framework**: Inertia.js
-   **Build Tool**: Vite
-   **Styling**: Tailwind CSS

## Prerequisites

Before you begin, ensure you have the following installed:

-   PHP 8.2 or higher with MySQL extension
-   Composer
-   Node.js 18 or higher
-   npm or yarn
-   MySQL 8.0 or higher (Laragon recommended for Windows)

## Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd GrowithBI
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**

    ```bash
    npm install
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Database setup**
   Create MySQL database named `growithbi`, then run:
    ```bash
    php artisan migrate
    ```

## Development

### Running the development server

You need to run both the Laravel backend and the Vite frontend development server:

1. **Start Laravel server**

    ```bash
    php artisan serve
    ```

    This will start the backend server at `http://localhost:8000`

2. **Start Vite development server** (in a separate terminal)

    ```bash
    npm run dev
    ```

    This will start the frontend build tool with hot module replacement

3. **Access the application**
   Open your browser and visit `http://localhost:8000`

### Available Scripts

-   `npm run dev` - Start Vite development server with HMR
-   `npm run build` - Build for production
-   `npm run preview` - Preview production build locally

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php
│   └── ...
├── resources/
│   ├── js/
│   │   ├── Pages/           # Vue.js page components
│   │   ├── Layouts/         # Layout components
│   │   └── app.js          # Main JavaScript entry point
│   └── views/
│       └── app.blade.php   # Root Blade template
├── routes/
│   └── web.php             # Web routes
└── ...
```

## Features

### Current Pages

-   **Welcome** (`/`) - Landing page with modern design
-   **About** (`/about`) - About page with company information

### Layout System

The application uses a reusable layout system with:

-   `AppLayout.vue` - Main application layout with navigation and footer
-   Responsive design with Tailwind CSS
-   Clean, modern UI components

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.
