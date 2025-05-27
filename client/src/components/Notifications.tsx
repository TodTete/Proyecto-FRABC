
import React from 'react';
import { useDocuments } from '@/contexts/DocumentContext';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Bell, Star, FileText, Clock, Check } from 'lucide-react';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const Notifications: React.FC = () => {
  const { notifications, markNotificationAsRead, updateDocumentStatus } = useDocuments();

  const handleMarkAsRead = (notificationId: string) => {
    markNotificationAsRead(notificationId);
  };

  const handleMarkAsViewed = (documentId: string, userId: string) => {
    // En un proyecto real, esto vendría del contexto de usuario actual
    const currentUserId = '2'; // Simulamos que es el usuario logueado
    updateDocumentStatus(documentId, currentUserId, 'pending');
  };

  const handleMarkAsConfirmed = (documentId: string, userId: string) => {
    const currentUserId = '2';
    updateDocumentStatus(documentId, currentUserId, 'delivered');
  };

  const formatTime = (timestamp: string) => {
    try {
      return formatDistanceToNow(new Date(timestamp), { 
        addSuffix: true, 
        locale: es 
      });
    } catch {
      return 'Hace un momento';
    }
  };

  const unreadNotifications = notifications.filter(n => !n.isRead);
  const readNotifications = notifications.filter(n => n.isRead);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Notificaciones</h1>
        <p className="text-gray-600">Mantente al día con los documentos recibidos</p>
      </div>

      {/* Estadísticas */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Sin Leer</CardTitle>
            <Bell className="h-4 w-4 text-red-500" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-red-600">{unreadNotifications.length}</div>
            <p className="text-xs text-muted-foreground">notificaciones pendientes</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Urgentes</CardTitle>
            <Star className="h-4 w-4 text-yellow-500" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-yellow-600">
              {notifications.filter(n => n.isUrgent && !n.isRead).length}
            </div>
            <p className="text-xs text-muted-foreground">documentos urgentes</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total</CardTitle>
            <FileText className="h-4 w-4 text-blue-500" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-blue-600">{notifications.length}</div>
            <p className="text-xs text-muted-foreground">notificaciones recibidas</p>
          </CardContent>
        </Card>
      </div>

      {/* Notificaciones sin leer */}
      {unreadNotifications.length > 0 && (
        <div className="space-y-4">
          <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <Bell className="h-5 w-5 text-red-500" />
            Sin Leer ({unreadNotifications.length})
          </h2>
          
          <div className="space-y-3">
            {unreadNotifications.map((notification) => (
              <Card key={notification.id} className="border-l-4 border-l-red-500 bg-red-50 hover:shadow-md transition-shadow">
                <CardContent className="p-4">
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1 space-y-2">
                      <div className="flex items-center gap-2">
                        <h3 className="font-semibold text-gray-900">
                          {notification.documentTitle}
                        </h3>
                        {notification.isUrgent && (
                          <Badge className="bg-yellow-500 hover:bg-yellow-600">
                            <Star className="h-3 w-3 mr-1" />
                            Urgente
                          </Badge>
                        )}
                      </div>
                      
                      <p className="text-gray-700">{notification.message}</p>
                      
                      <div className="flex items-center gap-4 text-sm text-gray-600">
                        <span>De: <strong>{notification.senderName}</strong></span>
                        <span className="flex items-center gap-1">
                          <Clock className="h-4 w-4" />
                          {formatTime(notification.timestamp)}
                        </span>
                      </div>
                    </div>

                    <div className="flex flex-col gap-2">
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => handleMarkAsViewed(notification.documentId, '2')}
                        className="text-yellow-600 border-yellow-300 hover:bg-yellow-50"
                      >
                        Visualizar
                      </Button>
                      
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => handleMarkAsConfirmed(notification.documentId, '2')}
                        className="text-green-600 border-green-300 hover:bg-green-50"
                      >
                        <Check className="h-4 w-4 mr-1" />
                        Confirmar
                      </Button>
                      
                      <Button
                        variant="ghost"
                        size="sm"
                        onClick={() => handleMarkAsRead(notification.id)}
                        className="text-gray-500 hover:text-gray-700"
                      >
                        Marcar leída
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      )}

      {/* Notificaciones leídas */}
      {readNotifications.length > 0 && (
        <div className="space-y-4">
          <h2 className="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <Check className="h-5 w-5 text-green-500" />
            Leídas ({readNotifications.length})
          </h2>
          
          <div className="space-y-3">
            {readNotifications.map((notification) => (
              <Card key={notification.id} className="opacity-75 hover:opacity-100 transition-opacity">
                <CardContent className="p-4">
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1 space-y-2">
                      <div className="flex items-center gap-2">
                        <h3 className="font-medium text-gray-700">
                          {notification.documentTitle}
                        </h3>
                        {notification.isUrgent && (
                          <Badge variant="secondary" className="text-yellow-600">
                            <Star className="h-3 w-3 mr-1" />
                            Urgente
                          </Badge>
                        )}
                      </div>
                      
                      <p className="text-gray-600">{notification.message}</p>
                      
                      <div className="flex items-center gap-4 text-sm text-gray-500">
                        <span>De: {notification.senderName}</span>
                        <span className="flex items-center gap-1">
                          <Clock className="h-4 w-4" />
                          {formatTime(notification.timestamp)}
                        </span>
                      </div>
                    </div>

                    <Badge variant="outline" className="text-green-600 border-green-300">
                      <Check className="h-3 w-3 mr-1" />
                      Leída
                    </Badge>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      )}

      {/* Estado vacío */}
      {notifications.length === 0 && (
        <Card>
          <CardContent className="flex flex-col items-center justify-center py-12">
            <Bell className="h-12 w-12 text-gray-400 mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">
              No tienes notificaciones
            </h3>
            <p className="text-gray-500 text-center">
              Cuando recibas documentos, aparecerán las notificaciones aquí
            </p>
          </CardContent>
        </Card>
      )}
    </div>
  );
};

export default Notifications;
