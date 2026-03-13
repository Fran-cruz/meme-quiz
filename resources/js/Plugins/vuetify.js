// Vuetify configuration
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { mdi } from 'vuetify/iconsets/mdi'

export default createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        sets: { mdi },
    },
    theme: {
        defaultTheme: 'dark',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#22c55e',
                    secondary: '#fbbf24',
                    accent: '#22c55e',
                    error: '#ef4444',
                    info: '#3b82f6',
                    success: '#10b981',
                    warning: '#f59e0b',
                    background: '#ffffff',
                    surface: '#f9fafb',
                    'on-primary': '#ffffff',
                    'on-secondary': '#1f2937',
                    'on-background': '#2d3748',
                    'on-surface': '#2d3748',
                    grey50: '#f9fafb',
                    grey100: '#f3f4f6',
                    grey200: '#e5e7eb',
                    grey300: '#d1d5db',
                    grey400: '#9ca3af',
                    grey500: '#6b7280',
                    grey600: '#4b5563',
                    grey700: '#374151',
                    grey800: '#1f2937',
                    grey900: '#111827',
                },
            },
            dark: {
                dark: true,
                colors: {
                    // Solo strings, nada de objetos
                    primary: '#00FF41',      // Verde Neón
                    secondary: '#00E5FF',    // Azul Cian
                    accent: '#FFFF00',       // Amarillo
                    error: '#FF3131',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FB8C00',

                    // El fondo negro absoluto que querías
                    background: '#000000',
                    surface: '#121212',      // Color para las "cajas" de los inputs

                    'on-background': '#FFFFFF',
                    'on-surface': '#FFFFFF',
                    'on-primary': '#000000',
                },
            },
        },
    },
})
