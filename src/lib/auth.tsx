import React, { createContext, useContext, useEffect, useState } from 'react';
import { db, seedDefaultUser, type AppUser } from './local-db';

export type UserRole = 'cashier' | 'manager' | 'super_admin';

interface AuthContextType {
  user: AppUser | null;
  role: UserRole;
  loading: boolean;
  signIn: (email: string, password: string) => Promise<{ error?: string }>;
  signOut: () => void;
  hasPermission: (requiredRole: UserRole) => boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

const ROLE_HIERARCHY: Record<UserRole, number> = {
  cashier: 1,
  manager: 2,
  super_admin: 3,
};

const SESSION_KEY = 'city_pos_session';

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<AppUser | null>(null);
  const [role, setRole] = useState<UserRole>('cashier');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    initAuth();
  }, []);

  async function initAuth() {
    // Ensure default admin exists
    await seedDefaultUser();

    // Check saved session
    const savedId = localStorage.getItem(SESSION_KEY);
    if (savedId) {
      const savedUser = await db.app_users.get(savedId);
      if (savedUser && savedUser.is_active) {
        setUser(savedUser);
        setRole(savedUser.role);
      }
    }
    setLoading(false);
  }

  async function signIn(email: string, password: string) {
    const found = await db.app_users.where('email').equals(email).first();
    if (!found) return { error: 'المستخدم غير موجود' };
    if (!found.is_active) return { error: 'الحساب معطل' };
    if (found.password !== password) return { error: 'كلمة المرور غير صحيحة' };

    setUser(found);
    setRole(found.role);
    localStorage.setItem(SESSION_KEY, found.id);
    return {};
  }

  function signOut() {
    setUser(null);
    setRole('cashier');
    localStorage.removeItem(SESSION_KEY);
  }

  function hasPermission(requiredRole: UserRole): boolean {
    return ROLE_HIERARCHY[role] >= ROLE_HIERARCHY[requiredRole];
  }

  return (
    <AuthContext.Provider value={{ user, role, loading, signIn, signOut, hasPermission }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth must be used within AuthProvider');
  return context;
}
