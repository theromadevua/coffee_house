export {};

declare global {
  interface AuthTokens {
    access_token: string;
    token_type: string;
    expires_in: number;
  }

  interface LoginResponse {
    data: AuthTokens;
  }
}
