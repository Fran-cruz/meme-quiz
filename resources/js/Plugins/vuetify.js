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
                    // Core colors
                    primary: '#22c55e',      // green
                    secondary: '#fbbf24',    // yellow
                    accent: '#3b82f6',       // blue accent
                    error: '#ef4444',        // red
                    info: '#60a5fa',         // lighter blue
                    success: '#10b981',      // green
                    warning: '#f59e0b',      // amber

                    // Surfaces & backgrounds
                    background: '#121212',   // very dark background
                    surface: '#1e1e2f',      // cards, dialogs, modals
                    'on-background': '#e0e0e0',  // text on background
                    'on-surface': '#f5f5f5',      // text on surface
                    'on-primary': '#ffffff',
                    'on-secondary': '#1f2937',

                    // Shades / greys for more flexibility
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

                    // Extra accent shades
                    primary_light: '#4ade80',
                    primary_dark: '#15803d',
                    secondary_light: '#fde68a',
                    secondary_dark: '#b45309',
                    accent_light: '#60a5fa',
                    accent_dark: '#1e3a8a',
                },
            },
        },
    },
})
