export {};

declare global {
  interface ApiResponse {
    data: any;
    message: string;
    success: boolean;
  }
}
