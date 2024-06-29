
const clientId = import.meta.env.VITE_AZURE_CLIENT_ID;
const redirectUri = import.meta.env.VITE_AZURE_REDIRECT_URI;

const AZURE_LOGIN_URI = 'https://login.live.com/oauth20_authorize.srf';
const AZURE_AUTH_URI = AZURE_LOGIN_URI + `?client_id=${clientId}&response_type=code&approval_prompt=auto&scope=Xboxlive.signin Xboxlive.offline_access&redirect_uri=${redirectUri}`;

export default AZURE_AUTH_URI;
