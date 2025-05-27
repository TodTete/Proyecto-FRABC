
import React, { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { toast } from 'sonner';
import { FileText, User, Lock, Shield } from 'lucide-react';

const LoginForm: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { login, isLoading } = useAuth();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!email || !password) {
      toast.error('Por favor ingresa email y contraseña');
      return;
    }

    const success = await login(email, password);
    if (success) {
      toast.success('¡Bienvenido al sistema DocPulse!');
    } else {
      toast.error('Credenciales incorrectas. Intenta con 123456 como contraseña.');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-corporate-50 via-blue-50 to-corporate-100 px-4">
      <div className="w-full max-w-md">
        {/* Logo y Header */}
        <div className="text-center mb-8">
          <div className="flex justify-center items-center mb-4">
            <div className="bg-corporate-600 p-3 rounded-full">
              <FileText className="h-8 w-8 text-white" />
            </div>
          </div>
          <h1 className="text-3xl font-bold text-corporate-800 mb-2">DocPulse</h1>
          <p className="text-gray-600">Sistema de Gestión de Documentos</p>
        </div>

        <Card className="shadow-2xl border-0 bg-white/95 backdrop-blur-sm">
          <CardHeader className="text-center pb-4">
            <CardTitle className="text-2xl font-bold text-corporate-800">
              Iniciar Sesión
            </CardTitle>
            <CardDescription className="text-corporate-600">
              Ingresa tus credenciales para acceder al sistema
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-5">
              <div className="space-y-2">
                <Label htmlFor="email" className="text-sm font-medium text-gray-700">
                  Correo Electrónico
                </Label>
                <div className="relative">
                  <User className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="email"
                    type="email"
                    placeholder="usuario@empresa.com"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    disabled={isLoading}
                    className="pl-10 h-11 border-gray-300 focus:border-corporate-500 focus:ring-corporate-500"
                  />
                </div>
              </div>
              
              <div className="space-y-2">
                <Label htmlFor="password" className="text-sm font-medium text-gray-700">
                  Contraseña
                </Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="password"
                    type="password"
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    disabled={isLoading}
                    className="pl-10 h-11 border-gray-300 focus:border-corporate-500 focus:ring-corporate-500"
                  />
                </div>
              </div>
              
              <Button 
                type="submit" 
                className="w-full h-11 bg-corporate-600 hover:bg-corporate-700 text-white font-medium"
                disabled={isLoading}
              >
                {isLoading ? (
                  <div className="flex items-center gap-2">
                    <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    Iniciando sesión...
                  </div>
                ) : (
                  'Iniciar Sesión'
                )}
              </Button>
            </form>
            
            {/* Usuarios de prueba */}
            <div className="mt-6 p-4 bg-gradient-to-r from-corporate-50 to-blue-50 rounded-lg border border-corporate-200">
              <div className="flex items-center gap-2 mb-3">
                <Shield className="h-4 w-4 text-corporate-600" />
                <span className="text-sm font-semibold text-corporate-700">Usuarios de Prueba</span>
              </div>
              
              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-gray-700">admin@empresa.com</p>
                    <p className="text-xs text-gray-500">Contraseña: 123456</p>
                  </div>
                  <Badge className="bg-purple-100 text-purple-700 border-purple-200">
                    Admin
                  </Badge>
                </div>
                
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-gray-700">maria@empresa.com</p>
                    <p className="text-xs text-gray-500">Contraseña: 123456</p>
                  </div>
                  <Badge className="bg-blue-100 text-blue-700 border-blue-200">
                    Usuario
                  </Badge>
                </div>
                
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-gray-700">carlos@empresa.com</p>
                    <p className="text-xs text-gray-500">Contraseña: 123456</p>
                  </div>
                  <Badge className="bg-blue-100 text-blue-700 border-blue-200">
                    Usuario
                  </Badge>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
        
        {/* Footer */}
        <div className="mt-8 text-center">
          <p className="text-xs text-gray-500">
            © 2024 DocPulse. Sistema de Gestión de Documentos
          </p>
        </div>
      </div>
    </div>
  );
};

export default LoginForm;
