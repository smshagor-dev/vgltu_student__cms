/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    prefix: 'tw-',
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
                xl: '3rem',
            },
            screens: {
                '2xl': '1280px',
            },
        },
        extend: {
            colors: {
                ink: '#10233f',
                navy: '#0b1f36',
                royal: '#163d68',
                azure: '#24598f',
                mist: '#edf3f8',
                cloud: '#f7fafc',
                gold: '#d0aa63',
                line: 'rgba(16, 35, 63, 0.12)',
            },
            fontFamily: {
                sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
            },
            boxShadow: {
                soft: '0 18px 40px rgba(16, 35, 63, 0.08)',
                panel: '0 24px 60px rgba(11, 31, 54, 0.16)',
                glow: '0 18px 45px rgba(36, 89, 143, 0.18)',
            },
            borderRadius: {
                panel: '1.75rem',
            },
            backgroundImage: {
                'hero-overlay': 'linear-gradient(110deg, rgba(7, 19, 33, 0.88) 0%, rgba(7, 19, 33, 0.68) 38%, rgba(7, 19, 33, 0.3) 100%)',
                'institutional-fade': 'linear-gradient(180deg, #ffffff 0%, #f6f9fc 100%)',
                'cta-overlay': 'linear-gradient(135deg, rgba(11,31,54,0.97), rgba(22,61,104,0.94))',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(18px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                float: 'float 5s ease-in-out infinite',
                'fade-up': 'fade-up 0.7s ease-out both',
            },
        },
    },
    plugins: [],
};
