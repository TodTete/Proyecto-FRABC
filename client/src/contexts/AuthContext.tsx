
import React, { createContext, useContext, useState, useEffect } from 'react';
import { User, CreateUserData, UpdateUserData } from '@/types';

interface AuthContextType {
  user: User | null;
  users: User[];
  login: (email: string, password: string) => Promise<boolean>;
  logout: () => void;
  createUser: (userData: CreateUserData) => Promise<boolean>;
  updateUser: (userId: string, userData: UpdateUserData) => Promise<boolean>;
  deleteUser: (userId: string) => Promise<boolean>;
  getAllUsers: () => User[];
  isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

// Datos simulados de usuarios con más información
const mockUsers: User[] = [
  {
    id: '1',
    name: 'Juan Pérez',
    email: 'admin@empresa.com',
    role: 'admin',
    career: 'Administración',
    avatar: '/api/placeholder/40/40',
    createdAt: '2024-01-15',
    status: 'active'
  },
  {
    id: '2',
    name: 'María García',
    email: 'maria@empresa.com',
    role: 'user',
    career: 'Sistemas',
    avatar: '/api/placeholder/40/40',
    createdAt: '2024-02-10',
    status: 'active'
  },
  {
    id: '3',
    name: 'Carlos López',
    email: 'carlos@empresa.com',
    role: 'user',
    career: 'Contabilidad',
    avatar: '/api/placeholder/40/40',
    createdAt: '2024-03-05',
    status: 'active'
  },
  {
    id: '4',
    name: 'Ana Rodríguez',
    email: 'ana@empresa.com',
    role: 'user',
    career: 'Recursos Humanos',
    avatar: '/api/placeholder/40/40',
    createdAt: '2024-03-20',
    status: 'inactive'
  }
];

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [users, setUsers] = useState<User[]>(mockUsers);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // Verificar si hay un usuario guardado en localStorage
    const savedUser = localStorage.getItem('currentUser');
    const savedUsers = localStorage.getItem('allUsers');
    
    if (savedUser) {
      setUser(JSON.parse(savedUser));
    }
    
    if (savedUsers) {
      setUsers(JSON.parse(savedUsers));
    }
    
    setIsLoading(false);
  }, []);

  const login = async (email: string, password: string): Promise<boolean> => {
    setIsLoading(true);
    
    // Simulamos un delay de red
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    const foundUser = users.find(u => u.email === email && u.status === 'active');
    if (foundUser && password === '123456') {
      setUser(foundUser);
      localStorage.setItem('currentUser', JSON.stringify(foundUser));
      setIsLoading(false);
      return true;
    }
    
    setIsLoading(false);
    return false;
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('currentUser');
  };

  const createUser = async (userData: CreateUserData): Promise<boolean> => {
    try {
      // Verificar si el email ya existe
      if (users.some(u => u.email === userData.email)) {
        return false;
      }

      const newUser: User = {
        id: (users.length + 1).toString(),
        name: userData.name,
        email: userData.email,
        role: userData.role,
        career: userData.career,
        avatar: '/api/placeholder/40/40',
        createdAt: new Date().toISOString().split('T')[0],
        status: 'active'
      };

      const updatedUsers = [...users, newUser];
      setUsers(updatedUsers);
      localStorage.setItem('allUsers', JSON.stringify(updatedUsers));
      
      return true;
    } catch (error) {
      console.error('Error creating user:', error);
      return false;
    }
  };

  const updateUser = async (userId: string, userData: UpdateUserData): Promise<boolean> => {
    try {
      const updatedUsers = users.map(u => 
        u.id === userId ? { ...u, ...userData } : u
      );
      
      setUsers(updatedUsers);
      localStorage.setItem('allUsers', JSON.stringify(updatedUsers));
      
      // Si el usuario actualizado es el usuario actual, actualizar también
      if (user && user.id === userId) {
        const updatedCurrentUser = { ...user, ...userData };
        setUser(updatedCurrentUser);
        localStorage.setItem('currentUser', JSON.stringify(updatedCurrentUser));
      }
      
      return true;
    } catch (error) {
      console.error('Error updating user:', error);
      return false;
    }
  };

  const deleteUser = async (userId: string): Promise<boolean> => {
    try {
      // No permitir eliminar al usuario actual
      if (user && user.id === userId) {
        return false;
      }

      const updatedUsers = users.filter(u => u.id !== userId);
      setUsers(updatedUsers);
      localStorage.setItem('allUsers', JSON.stringify(updatedUsers));
      
      return true;
    } catch (error) {
      console.error('Error deleting user:', error);
      return false;
    }
  };

  const getAllUsers = () => {
    return users;
  };

  return (
    <AuthContext.Provider value={{ 
      user, 
      users,
      login, 
      logout, 
      createUser,
      updateUser,
      deleteUser,
      getAllUsers,
      isLoading 
    }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
