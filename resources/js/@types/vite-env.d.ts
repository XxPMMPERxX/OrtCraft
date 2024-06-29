/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_APP_NAME: string,
  readonly VITE_FIREBASE_API_KEY: string
  readonly VITE_FIREBASE_AUTH_DOMAIN: string
  readonly VITE_FIREBASE_PROJECT_ID: string
  readonly VITE_FIREBASE_MESSAGING_SENDER_ID: string
  readonly VITE_FIREBASE_APP_ID: string

  readonly VITE_AZURE_CLIENT_ID: string,
  readonly VITE_AZURE_REDIRECT_URI: string,
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
