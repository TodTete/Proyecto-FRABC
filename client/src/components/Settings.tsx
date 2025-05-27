
import React, { useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Separator } from '@/components/ui/separator';
import { Settings as SettingsIcon, Save, Database, Mail, Bell } from 'lucide-react';
import { toast } from 'sonner';

const Settings: React.FC = () => {
  const [settings, setSettings] = useState({
    systemName: 'DocPulse',
    emailNotifications: true,
    urgentDocumentAlerts: true,
    autoBackup: false,
    maxFileSize: '10',
    allowedFormats: 'PDF',
    sessionTimeout: '120'
  });

  const handleSaveSettings = () => {
    // En un proyecto real, esto se enviaría al backend
    localStorage.setItem('systemSettings', JSON.stringify(settings));
    toast.success('Configuración guardada exitosamente');
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Configuración del Sistema</h1>
        <p className="text-gray-600">Administra la configuración general del sistema</p>
      </div>

      {/* Configuración General */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <SettingsIcon className="h-5 w-5" />
            Configuración General
          </CardTitle>
          <CardDescription>
            Configuración básica del sistema
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="systemName">Nombre del Sistema</Label>
              <Input
                id="systemName"
                value={settings.systemName}
                onChange={(e) => setSettings({...settings, systemName: e.target.value})}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="sessionTimeout">Tiempo de Sesión (minutos)</Label>
              <Input
                id="sessionTimeout"
                type="number"
                value={settings.sessionTimeout}
                onChange={(e) => setSettings({...settings, sessionTimeout: e.target.value})}
              />
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Configuración de Archivos */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Database className="h-5 w-5" />
            Configuración de Archivos
          </CardTitle>
          <CardDescription>
            Configuración para la gestión de documentos
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="maxFileSize">Tamaño Máximo de Archivo (MB)</Label>
              <Input
                id="maxFileSize"
                type="number"
                value={settings.maxFileSize}
                onChange={(e) => setSettings({...settings, maxFileSize: e.target.value})}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="allowedFormats">Formatos Permitidos</Label>
              <Input
                id="allowedFormats"
                value={settings.allowedFormats}
                onChange={(e) => setSettings({...settings, allowedFormats: e.target.value})}
                placeholder="PDF, DOC, DOCX"
              />
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Configuración de Notificaciones */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Bell className="h-5 w-5" />
            Configuración de Notificaciones
          </CardTitle>
          <CardDescription>
            Configuración del sistema de notificaciones
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="flex items-center justify-between">
            <div className="space-y-0.5">
              <Label>Notificaciones por Email</Label>
              <p className="text-sm text-gray-500">
                Enviar notificaciones por correo electrónico
              </p>
            </div>
            <Switch
              checked={settings.emailNotifications}
              onCheckedChange={(checked) => setSettings({...settings, emailNotifications: checked})}
            />
          </div>

          <Separator />

          <div className="flex items-center justify-between">
            <div className="space-y-0.5">
              <Label>Alertas de Documentos Urgentes</Label>
              <p className="text-sm text-gray-500">
                Notificaciones especiales para documentos urgentes
              </p>
            </div>
            <Switch
              checked={settings.urgentDocumentAlerts}
              onCheckedChange={(checked) => setSettings({...settings, urgentDocumentAlerts: checked})}
            />
          </div>

          <Separator />

          <div className="flex items-center justify-between">
            <div className="space-y-0.5">
              <Label>Respaldo Automático</Label>
              <p className="text-sm text-gray-500">
                Realizar respaldos automáticos de la base de datos
              </p>
            </div>
            <Switch
              checked={settings.autoBackup}
              onCheckedChange={(checked) => setSettings({...settings, autoBackup: checked})}
            />
          </div>
        </CardContent>
      </Card>

      {/* Información de Base de Datos */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Database className="h-5 w-5" />
            Información de Base de Datos
          </CardTitle>
          <CardDescription>
            Estado actual de la base de datos MariaDB
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label>Estado de Conexión</Label>
              <div className="flex items-center gap-2">
                <div className="h-2 w-2 bg-green-500 rounded-full"></div>
                <span className="text-sm">Conectado</span>
              </div>
            </div>
            <div className="space-y-2">
              <Label>Versión de MariaDB</Label>
              <p className="text-sm text-gray-600">10.6.16</p>
            </div>
            <div className="space-y-2">
              <Label>Último Respaldo</Label>
              <p className="text-sm text-gray-600">25/05/2024 - 14:30</p>
            </div>
            <div className="space-y-2">
              <Label>Espacio Utilizado</Label>
              <p className="text-sm text-gray-600">245 MB / 1 GB</p>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Guardar Configuración */}
      <div className="flex justify-end">
        <Button onClick={handleSaveSettings} className="bg-corporate-600 hover:bg-corporate-700">
          <Save className="h-4 w-4 mr-2" />
          Guardar Configuración
        </Button>
      </div>
    </div>
  );
};

export default Settings;
