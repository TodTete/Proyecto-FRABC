
import React, { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { AuthProvider } from '@/contexts/AuthContext';
import { DocumentProvider } from '@/contexts/DocumentContext';
import LoginForm from '@/components/LoginForm';
import Layout from '@/components/Layout';
import Dashboard from '@/components/Dashboard';
import Notifications from '@/components/Notifications';
import Profile from '@/components/Profile';
import UserManagement from '@/components/UserManagement';
import Settings from '@/components/Settings';

const AppContent: React.FC = () => {
  const { user, isLoading } = useAuth();
  const [currentSection, setCurrentSection] = useState<'dashboard' | 'notifications' | 'profile' | 'users' | 'settings'>('dashboard');

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-corporate-50 to-corporate-100">
        <div className="text-center">
          <div className="animate-spin rounded-full h-16 w-16 border-b-4 border-corporate-600 mx-auto mb-4"></div>
          <p className="text-gray-600 text-lg">Cargando sistema...</p>
        </div>
      </div>
    );
  }

  if (!user) {
    return <LoginForm />;
  }

  const renderContent = () => {
    switch (currentSection) {
      case 'dashboard':
        return <Dashboard />;
      case 'notifications':
        return <Notifications />;
      case 'profile':
        return <Profile />;
      case 'users':
        // Solo permitir acceso a administradores
        return user.role === 'admin' ? <UserManagement /> : <Dashboard />;
      case 'settings':
        // Solo permitir acceso a administradores
        return user.role === 'admin' ? <Settings /> : <Dashboard />;
      default:
        return <Dashboard />;
    }
  };

  return (
    <DocumentProvider>
      <Layout 
        currentSection={currentSection} 
        onSectionChange={(section) => {
          // Verificar permisos antes de cambiar de sección
          if ((section === 'users' || section === 'settings') && user.role !== 'admin') {
            return; // No permitir el cambio si no es admin
          }
          setCurrentSection(section);
        }}
      >
        {renderContent()}
      </Layout>
    </DocumentProvider>
  );
};

const Index = () => {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
};

export default Index;
