interface User {
  id: string;
  username: string;
  avatar?: string;
  isOnline?: boolean;
}

class AuthService {
  private static instance: AuthService;
  private currentUser: User | null = null;

  private constructor() {}

  static getInstance(): AuthService {
    if (!AuthService.instance) {
      AuthService.instance = new AuthService();
    }
    return AuthService.instance;
  }

  async login(username: string, password: string): Promise<User> {
    try {
      const response = await fetch('http://localhost:3001/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      });

      if (!response.ok) throw new Error('Login failed');
      
      const user = await response.json();
      this.currentUser = user;
      return user;
    } catch (error) {
      throw new Error('Authentication failed');
    }
  }

  getCurrentUser(): User | null {
    return this.currentUser;
  }

  isAuthenticated(): boolean {
    return !!this.currentUser;
  }

  logout() {
    this.currentUser = null;
  }
}

export default AuthService.getInstance();
