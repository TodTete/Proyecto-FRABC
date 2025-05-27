
import React, { useState } from 'react';
import { useDocuments } from '@/contexts/DocumentContext';
import { useAuth } from '@/contexts/AuthContext';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { DocumentFilters } from '@/types';
import { Star, Search, Plus, FileText, Users, Calendar } from 'lucide-react';
import UploadDocumentModal from './UploadDocumentModal';

const Dashboard: React.FC = () => {
  const { user } = useAuth();
  const { documents, filterDocuments } = useDocuments();
  const [showUploadModal, setShowUploadModal] = useState(false);
  const [filters, setFilters] = useState<DocumentFilters>({
    documentType: 'all',
    status: 'all'
  });

  const filteredDocuments = filterDocuments(filters);

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'delivered': return 'bg-status-delivered';
      case 'pending': return 'bg-status-pending';
      case 'process': return 'bg-status-process';
      default: return 'bg-gray-400';
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case 'delivered': return 'Entregado';
      case 'pending': return 'Pendiente';
      case 'process': return 'En proceso';
      default: return 'Desconocido';
    }
  };

  const getOverallStatus = (recipients: any[]) => {
    if (recipients.every(r => r.status === 'delivered')) return 'delivered';
    if (recipients.some(r => r.status === 'pending')) return 'pending';
    return 'process';
  };

  // Estadísticas
  const totalDocuments = documents.length;
  const urgentDocuments = documents.filter(d => d.isUrgent).length;
  const deliveredDocuments = documents.filter(d => getOverallStatus(d.recipients) === 'delivered').length;

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
          <p className="text-gray-600">Gestiona y supervisa tus documentos</p>
        </div>
        <Button 
          onClick={() => setShowUploadModal(true)}
          className="bg-corporate-600 hover:bg-corporate-700"
        >
          <Plus className="h-4 w-4 mr-2" />
          Subir Documento
        </Button>
      </div>

      {/* Estadísticas */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total Documentos</CardTitle>
            <FileText className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-corporate-700">{totalDocuments}</div>
            <p className="text-xs text-muted-foreground">documentos enviados</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Urgentes</CardTitle>
            <Star className="h-4 w-4 text-yellow-500" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-yellow-600">{urgentDocuments}</div>
            <p className="text-xs text-muted-foreground">documentos urgentes</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Entregados</CardTitle>
            <Users className="h-4 w-4 text-green-500" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-green-600">{deliveredDocuments}</div>
            <p className="text-xs text-muted-foreground">completamente entregados</p>
          </CardContent>
        </Card>
      </div>

      {/* Filtros */}
      <Card>
        <CardHeader>
          <CardTitle className="text-lg">Filtros de Búsqueda</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div className="relative">
              <Search className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
              <Input
                placeholder="Buscar por folio..."
                className="pl-10"
                value={filters.folio || ''}
                onChange={(e) => setFilters({ ...filters, folio: e.target.value })}
              />
            </div>
            
            <Input
              type="date"
              value={filters.dateFrom || ''}
              onChange={(e) => setFilters({ ...filters, dateFrom: e.target.value })}
            />
            
            <Select
              value={filters.documentType || 'all'}
              onValueChange={(value) => setFilters({ ...filters, documentType: value as any })}
            >
              <SelectTrigger>
                <SelectValue placeholder="Tipo de documento" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos</SelectItem>
                <SelectItem value="urgent">Urgente</SelectItem>
                <SelectItem value="ordinary">Ordinario</SelectItem>
              </SelectContent>
            </Select>

            <Input
              placeholder="Carrera..."
              value={filters.career || ''}
              onChange={(e) => setFilters({ ...filters, career: e.target.value })}
            />
          </div>
        </CardContent>
      </Card>

      {/* Lista de documentos */}
      <div className="space-y-4">
        <h2 className="text-xl font-semibold text-gray-900">Documentos Enviados</h2>
        
        {filteredDocuments.length === 0 ? (
          <Card>
            <CardContent className="flex flex-col items-center justify-center py-12">
              <FileText className="h-12 w-12 text-gray-400 mb-4" />
              <p className="text-gray-500 text-center">
                No se encontraron documentos con los filtros aplicados
              </p>
            </CardContent>
          </Card>
        ) : (
          <div className="grid gap-4">
            {filteredDocuments.map((document) => {
              const overallStatus = getOverallStatus(document.recipients);
              return (
                <Card key={document.id} className="hover:shadow-md transition-shadow">
                  <CardContent className="p-6">
                    <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                      <div className="flex-1 space-y-2">
                        <div className="flex items-center gap-2">
                          <h3 className="font-semibold text-lg text-gray-900">
                            {document.title}
                          </h3>
                          {document.isUrgent && (
                            <Star className="h-5 w-5 text-yellow-500 animate-pulse-star" />
                          )}
                        </div>
                        
                        <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                          <span className="flex items-center gap-1">
                            <strong>Folio:</strong> {document.folio}
                          </span>
                          <span className="flex items-center gap-1">
                            <Calendar className="h-4 w-4" />
                            {document.uploadDate}
                          </span>
                          <span className="flex items-center gap-1">
                            <strong>Carrera:</strong> {document.career}
                          </span>
                        </div>
                        
                        <p className="text-gray-700">{document.description}</p>
                      </div>

                      <div className="flex flex-col items-end gap-3">
                        <div className="flex items-center gap-2">
                          <div className={`w-3 h-3 rounded-full ${getStatusColor(overallStatus)}`} />
                          <span className="text-sm font-medium">
                            {getStatusText(overallStatus)}
                          </span>
                        </div>
                        
                        <div className="text-right">
                          <p className="text-sm text-gray-600">Destinatarios:</p>
                          <div className="flex flex-wrap gap-1 mt-1">
                            {document.recipients.map((recipient) => (
                              <Badge
                                key={recipient.userId}
                                variant="outline"
                                className={`text-xs ${getStatusColor(recipient.status)} text-white border-none`}
                              >
                                {recipient.userName}
                              </Badge>
                            ))}
                          </div>
                        </div>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              );
            })}
          </div>
        )}
      </div>

      {/* Modal de subida */}
      <UploadDocumentModal
        isOpen={showUploadModal}
        onClose={() => setShowUploadModal(false)}
      />
    </div>
  );
};

export default Dashboard;
