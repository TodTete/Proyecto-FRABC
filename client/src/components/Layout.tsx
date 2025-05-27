
import React, { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { useDocuments } from '@/contexts/DocumentContext';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { 
  Bell, 
  FileText, 
  Home, 
  User, 
  LogOut, 
  Menu,
  X,
  Users,
  Settings,
  Upload
} from 'lucide-react';

interface LayoutProps {
  children: React.ReactNode;
  currentSection: 'dashboard' | 'notifications' | 'profile' | 'users' | 'settings';
  onSectionChange: (section: 'dashboard' | 'notifications' | 'profile' | 'users' | 'settings') => void;
}

const Layout: React.FC<LayoutProps> = ({ children, currentSection, onSectionChange }) => {
  const { user, logout } = useAuth();
  const { getUnreadNotificationsCount } = useDocuments();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const unreadCount = getUnreadNotificationsCount();

  // Navegación base para todos los usuarios
  const baseNavigation = [
    {
      name: 'Dashboard',
      key: 'dashboard' as const,
      icon: Home,
      current: currentSection === 'dashboard'
    },
    {
      name: 'Notificaciones',
      key: 'notifications' as const,
      icon: Bell,
      current: currentSection === 'notifications',
      badge: unreadCount > 0 ? unreadCount : undefined
    },
    {
      name: 'Perfil',
      key: 'profile' as const,
      icon: User,
      current: currentSection === 'profile'
    }
  ];

  // Navegación adicional para administradores
  const adminNavigation = [
    {
      name: 'Gestión de Usuarios',
      key: 'users' as const,
      icon: Users,
      current: currentSection === 'users'
    },
    {
      name: 'Configuración',
      key: 'settings' as const,
      icon: Settings,
      current: currentSection === 'settings'
    }
  ];

  // Combinar navegación según el rol
  const navigation = user?.role === 'admin' 
    ? [...baseNavigation, ...adminNavigation]
    : baseNavigation;

  return (
    <div className="h-screen bg-gray-50 flex">
      {/* Sidebar para móvil */}
      {sidebarOpen && (
        <div className="fixed inset-0 flex z-40 md:hidden">
          <div className="fixed inset-0 bg-gray-600 bg-opacity-75" onClick={() => setSidebarOpen(false)} />
          <div className="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div className="absolute top-0 right-0 -mr-12 pt-2">
              <Button
                variant="ghost"
                size="icon"
                className="text-white"
                onClick={() => setSidebarOpen(false)}
              >
                <X className="h-6 w-6" />
              </Button>
            </div>
            <SidebarContent 
              navigation={navigation} 
              user={user} 
              onSectionChange={onSectionChange}
              onLogout={logout}
            />
          </div>
        </div>
      )}

      {/* Sidebar para desktop */}
      <div className="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
        <div className="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200 shadow-lg">
          <SidebarContent 
            navigation={navigation} 
            user={user} 
            onSectionChange={onSectionChange}
            onLogout={logout}
          />
        </div>
      </div>

      {/* Contenido principal */}
      <div className="flex flex-col w-0 flex-1 md:ml-64">
        {/* Header móvil */}
        <div className="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-white border-b border-gray-200">
          <div className="flex items-center justify-between p-4">
            <Button
              variant="ghost"
              size="icon"
              onClick={() => setSidebarOpen(true)}
            >
              <Menu className="h-6 w-6" />
            </Button>
            <div className="flex items-center space-x-2">
              <Badge variant={user?.role === 'admin' ? 'default' : 'secondary'}>
                {user?.role === 'admin' ? 'Administrador' : 'Usuario'}
              </Badge>
            </div>
          </div>
        </div>

        {/* Contenido */}
        <main className="flex-1 relative overflow-y-auto focus:outline-none bg-gray-50">
          <div className="py-6 px-4 sm:px-6 lg:px-8">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
};

const SidebarContent: React.FC<{
  navigation: any[];
  user: any;
  onSectionChange: (section: any) => void;
  onLogout: () => void;
}> = ({ navigation, user, onSectionChange, onLogout }) => {
  return (
    <>
      <div className="flex items-center h-16 flex-shrink-0 px-4 bg-gradient-to-r from-corporate-800 to-corporate-900">
        <FileText className="h-8 w-8 text-white" />
        <span className="ml-2 text-white font-bold text-lg">DocPulse</span>
      </div>
      
      <div className="flex-1 flex flex-col pt-5 pb-4">
        <div className="flex items-center flex-shrink-0 px-4 mb-4">
          <Avatar className="h-10 w-10">
            <AvatarFallback className="bg-corporate-600 text-white font-semibold">
              {user?.name?.charAt(0) || 'U'}
            </AvatarFallback>
          </Avatar>
          <div className="ml-3 flex-1">
            <p className="text-sm font-medium text-gray-700">{user?.name}</p>
            <div className="flex items-center gap-2">
              <p className="text-xs text-gray-500">{user?.career}</p>
              <Badge 
                variant={user?.role === 'admin' ? 'default' : 'secondary'} 
                className="text-xs"
              >
                {user?.role === 'admin' ? 'Admin' : 'Usuario'}
              </Badge>
            </div>
          </div>
        </div>
        
        <nav className="mt-5 flex-1 px-2 bg-white space-y-1">
          {navigation.map((item) => {
            const Icon = item.icon;
            return (
              <Button
                key={item.name}
                variant={item.current ? "secondary" : "ghost"}
                className={`w-full justify-start ${
                  item.current 
                    ? 'bg-corporate-100 text-corporate-900 border-r-4 border-corporate-600 font-medium' 
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                }`}
                onClick={() => onSectionChange(item.key)}
              >
                <Icon className="mr-3 h-5 w-5" />
                {item.name}
                {item.badge && (
                  <Badge className="ml-auto bg-red-500 hover:bg-red-600 text-white">
                    {item.badge}
                  </Badge>
                )}
              </Button>
            );
          })}
        </nav>
      </div>
      
      <div className="flex-shrink-0 flex border-t border-gray-200 p-4">
        <Button
          variant="ghost"
          className="w-full justify-start text-red-600 hover:text-red-700 hover:bg-red-50"
          onClick={onLogout}
        >
          <LogOut className="mr-3 h-5 w-5" />
          Cerrar Sesión
        </Button>
      </div>
    </>
  );
};

export default Layout;
