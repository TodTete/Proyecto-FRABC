
import React, { useState } from 'react';
import { useDocuments } from '@/contexts/DocumentContext';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DocumentRecipient } from '@/types';
import { toast } from 'sonner';
import { Upload, Star } from 'lucide-react';

interface UploadDocumentModalProps {
  isOpen: boolean;
  onClose: () => void;
}

// Usuarios disponibles para etiquetar (en un proyecto real vendría de la API)
const availableUsers = [
  { id: '2', name: 'María García', career: 'Sistemas' },
  { id: '3', name: 'Carlos López', career: 'Contabilidad' },
  { id: '4', name: 'Ana Martínez', career: 'Administración' },
  { id: '5', name: 'Luis Hernández', career: 'Sistemas' }
];

const careers = ['Administración', 'Sistemas', 'Contabilidad', 'Recursos Humanos'];
const documentTypes = ['Informe', 'Manual', 'Política', 'Procedimiento', 'Circular', 'Memorándum'];

const UploadDocumentModal: React.FC<UploadDocumentModalProps> = ({ isOpen, onClose }) => {
  const { uploadDocument } = useDocuments();
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    career: '',
    documentType: '',
    isUrgent: false,
    file: null as File | null
  });
  const [selectedUsers, setSelectedUsers] = useState<string[]>([]);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.type !== 'application/pdf') {
        toast.error('Solo se permiten archivos PDF');
        return;
      }
      setFormData({ ...formData, file });
    }
  };

  const handleUserToggle = (userId: string) => {
    setSelectedUsers(prev => 
      prev.includes(userId) 
        ? prev.filter(id => id !== userId)
        : [...prev, userId]
    );
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    // Validaciones
    if (!formData.title || !formData.description || !formData.career || 
        !formData.documentType || !formData.file || selectedUsers.length === 0) {
      toast.error('Por favor completa todos los campos obligatorios');
      setIsSubmitting(false);
      return;
    }

    try {
      // Simular subida de archivo
      await new Promise(resolve => setTimeout(resolve, 1000));

      const recipients: DocumentRecipient[] = selectedUsers.map(userId => {
        const user = availableUsers.find(u => u.id === userId);
        return {
          userId,
          userName: user?.name || 'Usuario desconocido',
          status: 'process' as const
        };
      });

      const documentData = {
        folio: `DOC-${new Date().getFullYear()}-${String(Date.now()).slice(-3)}`,
        title: formData.title,
        description: formData.description,
        fileUrl: '/mock-document.pdf',
        fileName: formData.file.name,
        isUrgent: formData.isUrgent,
        career: formData.career,
        documentType: formData.documentType,
        recipients
      };

      uploadDocument(documentData);
      
      toast.success('Documento subido exitosamente');
      
      // Resetear formulario
      setFormData({
        title: '',
        description: '',
        career: '',
        documentType: '',
        isUrgent: false,
        file: null
      });
      setSelectedUsers([]);
      onClose();
    } catch (error) {
      toast.error('Error al subir el documento');
    } finally {
      setIsSubmitting(false);
    }
  };

  const resetForm = () => {
    setFormData({
      title: '',
      description: '',
      career: '',
      documentType: '',
      isUrgent: false,
      file: null
    });
    setSelectedUsers([]);
  };

  return (
    <Dialog open={isOpen} onOpenChange={(open) => {
      if (!open) {
        resetForm();
        onClose();
      }
    }}>
      <DialogContent className="max-w-2xl max-h-[80vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Upload className="h-5 w-5" />
            Subir Nuevo Documento
          </DialogTitle>
          <DialogDescription>
            Completa la información del documento y selecciona los destinatarios
          </DialogDescription>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="title">Título del Documento *</Label>
              <Input
                id="title"
                value={formData.title}
                onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                placeholder="Ej: Informe Trimestral Q1"
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="career">Carrera *</Label>
              <Select
                value={formData.career}
                onValueChange={(value) => setFormData({ ...formData, career: value })}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Selecciona una carrera" />
                </SelectTrigger>
                <SelectContent>
                  {careers.map((career) => (
                    <SelectItem key={career} value={career}>
                      {career}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="description">Descripción *</Label>
            <Textarea
              id="description"
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
              placeholder="Describe brevemente el contenido del documento..."
              rows={3}
              required
            />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="documentType">Tipo de Documento *</Label>
              <Select
                value={formData.documentType}
                onValueChange={(value) => setFormData({ ...formData, documentType: value })}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Selecciona el tipo" />
                </SelectTrigger>
                <SelectContent>
                  {documentTypes.map((type) => (
                    <SelectItem key={type} value={type}>
                      {type}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="file">Archivo PDF *</Label>
              <Input
                id="file"
                type="file"
                accept=".pdf"
                onChange={handleFileChange}
                required
              />
            </div>
          </div>

          <div className="flex items-center space-x-2">
            <Checkbox
              id="urgent"
              checked={formData.isUrgent}
              onCheckedChange={(checked) => setFormData({ ...formData, isUrgent: checked as boolean })}
            />
            <Label htmlFor="urgent" className="flex items-center gap-2">
              <Star className="h-4 w-4 text-yellow-500" />
              Marcar como urgente
            </Label>
          </div>

          <div className="space-y-3">
            <Label>Destinatarios * (selecciona al menos uno)</Label>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-lg p-3">
              {availableUsers.map((user) => (
                <div key={user.id} className="flex items-center space-x-2">
                  <Checkbox
                    id={`user-${user.id}`}
                    checked={selectedUsers.includes(user.id)}
                    onCheckedChange={() => handleUserToggle(user.id)}
                  />
                  <Label htmlFor={`user-${user.id}`} className="text-sm">
                    {user.name} <span className="text-gray-500">({user.career})</span>
                  </Label>
                </div>
              ))}
            </div>
            {selectedUsers.length > 0 && (
              <p className="text-sm text-gray-600">
                {selectedUsers.length} destinatario(s) seleccionado(s)
              </p>
            )}
          </div>

          <DialogFooter>
            <Button
              type="button"
              variant="outline"
              onClick={() => {
                resetForm();
                onClose();
              }}
              disabled={isSubmitting}
            >
              Cancelar
            </Button>
            <Button
              type="submit"
              className="bg-corporate-600 hover:bg-corporate-700"
              disabled={isSubmitting}
            >
              {isSubmitting ? 'Subiendo...' : 'Subir Documento'}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
};

export default UploadDocumentModal;
