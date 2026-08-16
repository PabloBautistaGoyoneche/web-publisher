/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php",
    "./src/Views/**/*.php",
    "./src/Views/*.php",
    "./src/*.php"
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Outfit', 'Inter', 'sans-serif'],
      },
      colors: {
        brand: {
          50: 'rgb(var(--brand-50, 245 243 255) / <alpha-value>)',
          100: 'rgb(var(--brand-100, 237 233 254) / <alpha-value>)',
          200: 'rgb(var(--brand-200, 221 214 254) / <alpha-value>)',
          300: 'rgb(var(--brand-300, 196 181 253) / <alpha-value>)',
          400: 'rgb(var(--brand-400, 167 139 250) / <alpha-value>)',
          500: 'rgb(var(--brand-500, 139 92 246) / <alpha-value>)',
          600: 'rgb(var(--brand-600, 124 58 237) / <alpha-value>)',
          700: 'rgb(var(--brand-700, 109 40 217) / <alpha-value>)',
          800: 'rgb(var(--brand-800, 91 33 182) / <alpha-value>)',
          900: 'rgb(var(--brand-900, 76 29 149) / <alpha-value>)',
          950: 'rgb(var(--brand-950, 46 16 101) / <alpha-value>)',
        },
        secondary: {
          50: 'rgb(var(--secondary-50, 245 243 255) / <alpha-value>)',
          100: 'rgb(var(--secondary-100, 237 233 254) / <alpha-value>)',
          200: 'rgb(var(--secondary-200, 221 214 254) / <alpha-value>)',
          300: 'rgb(var(--secondary-300, 196 181 253) / <alpha-value>)',
          400: 'rgb(var(--secondary-400, 167 139 250) / <alpha-value>)',
          500: 'rgb(var(--secondary-500, 139 92 246) / <alpha-value>)',
          600: 'rgb(var(--secondary-600, 79 70 229) / <alpha-value>)',
          700: 'rgb(var(--secondary-700, 67 56 202) / <alpha-value>)',
          800: 'rgb(var(--secondary-800, 55 48 163) / <alpha-value>)',
          900: 'rgb(var(--secondary-900, 49 46 129) / <alpha-value>)',
          950: 'rgb(var(--secondary-950, 30 27 75) / <alpha-value>)',
        },
        sitebg: 'rgb(var(--sitebg, 248 250 252) / <alpha-value>)',
        siteheader: 'rgb(var(--siteheader, 255 255 255) / <alpha-value>)',
        sitefooter: 'rgb(var(--sitefooter, 255 255 255) / <alpha-value>)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
