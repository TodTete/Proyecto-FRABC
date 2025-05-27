
import React, { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { User, Mail, Briefcase, Shield, Edit2, Save, X } from 'lucide-react';
import { toast } from 'sonner';

const Profile: React.FC = () => {
  const { user } = useAuth();
  const [isEditing, setIsEditing] = useState(false);
  const [editData, setEditData] = useState({
    name: user?.name || '',
    email: user?.email || '',
    career: user?.career || ''
  });

  const handleSave = () => {
    // En un proyecto real, aquí se haría la llamada a la API
    toast.success('Perfil actualizado correctamente');
    setIsEditing(false);
  };

  const handleCancel = () => {
    setEditData({
      name: user?.name || '',
      email: user?.email || '',
      career: user?.career || ''
    });
    setIsEditing(false);
  };

  if (!user) return null;

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Perfil Personal</h1>
        <p className="text-gray-600">Gestiona tu información personal y configuración</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Información del perfil */}
        <div className="lg:col-span-2">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <div>
                  <CardTitle>Información Personal</CardTitle>
                  <CardDescription>
                    Actualiza tu información de perfil aquí
                  </CardDescription>
                </div>
                {!isEditing ? (
                  <Button
                    variant="outline"
                    onClick={() => setIsEditing(true)}
                    className="flex items-center gap-2"
                  >
                    <Edit2 className="h-4 w-4" />
                    Editar
                  </Button>
                ) : (
                  <div className="flex gap-2">
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={handleCancel}
                      className="flex items-center gap-2"
                    >
                      <X className="h-4 w-4" />
                      Cancelar
                    </Button>
                    <Button
                      size="sm"
                      onClick={handleSave}
                      className="flex items-center gap-2 bg-corporate-600 hover:bg-corporate-700"
                    >
                      <Save className="h-4 w-4" />
                      Guardar
                    </Button>
                  </div>
                )}
              </div>
            </CardHeader>
            <CardContent className="space-y-6">
              <div className="flex items-center gap-6">
                <Avatar className="h-20 w-20">
                  <AvatarFallback className="bg-corporate-600 text-white text-xl">
                    {user.name.charAt(0)}
                  </AvatarFallback>
                </Avatar>
                <div>
                  <h3 className="text-lg font-medium text-gray-900">{user.name}</h3>
                  <p className="text-gray-600">{user.email}</p>
                  <Badge className="mt-1" variant={user.role === 'admin' ? 'default' : 'secondary'}>
                    {user.role === 'admin' ? 'Administrador' : 'Usuario'}
                  </Badge>
                </div>
              </div>

              <Separator />

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <Label htmlFor="name">Nombre Completo</Label>
                  {isEditing ? (
                    <Input
                      id="name"
                      value={editData.name}
                      onChange={(e) => setEditData({ ...editData, name: e.target.value })}
                    />
                  ) : (
                    <div className="flex items-center gap-2 p-2 border rounded">
                      <User className="h-4 w-4 text-gray-500" />
                      <span>{user.name}</span>
                    </div>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="email">Correo Electrónico</Label>
                  {isEditing ? (
                    <Input
                      id="email"
                      type="email"
                      value={editData.email}
                      onChange={(e) => setEditData({ ...editData, email: e.target.value })}
                    />
                  ) : (
                    <div className="flex items-center gap-2 p-2 border rounded">
                      <Mail className="h-4 w-4 text-gray-500" />
                      <span>{user.email}</span>
                    </div>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="career">Carrera/Departamento</Label>
                  {isEditing ? (
                    <Input
                      id="career"
                      value={editData.career}
                      onChange={(e) => setEditData({ ...editData, career: e.target.value })}
                    />
                  ) : (
                    <div className="flex items-center gap-2 p-2 border rounded">
                      <Briefcase className="h-4 w-4 text-gray-500" />
                      <span>{user.career}</span>
                    </div>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="role">Rol del Sistema</Label>
                  <div className="flex items-center gap-2 p-2 border rounded bg-gray-50">
                    <Shield className="h-4 w-4 text-gray-500" />
                    <span className="capitalize">
                      {user.role === 'admin' ? 'Administrador' : 'Usuario'}
                    </span>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Estadísticas del usuario */}
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Estadísticas</CardTitle>
              <CardDescription>Tu actividad en el sistema</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Documentos enviados</span>
                <span className="font-semibold text-corporate-700">12</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Documentos recibidos</span>
                <span className="font-semibold text-corporate-700">8</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Pendientes por revisar</span>
                <span className="font-semibold text-yellow-600">3</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Documentos urgentes</span>
                <span className="font-semibold text-red-600">1</span>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Configuración</CardTitle>
              <CardDescription>Preferencias del sistema</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Notificaciones por email</span>
                <Badge variant="outline" className="text-green-600 border-green-300">
                  Activado
                </Badge>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Recordatorios automáticos</span>
                <Badge variant="outline" className="text-green-600 border-green-300">
                  Activado
                </Badge>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-gray-600">Firma digital</span>
                <Badge variant="outline" className="text-gray-600 border-gray-300">
                  No configurada
                </Badge>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Seguridad</CardTitle>
              <CardDescription>Configuración de seguridad</CardDescription>
            </CardHeader>
            <CardContent className="space-y-3">
              <Button variant="outline" className="w-full justify-start">
                Cambiar contraseña
              </Button>
              <Button variant="outline" className="w-full justify-start">
                Configurar 2FA
              </Button>
              <Button variant="outline" className="w-full justify-start">
                Historial de sesiones
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
};

export default Profile;
