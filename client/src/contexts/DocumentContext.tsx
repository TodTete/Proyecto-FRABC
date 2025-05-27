
import React, { createContext, useContext, useState, useEffect } from 'react';
import { Document, DocumentFilters, Notification } from '@/types';
import { useAuth } from './AuthContext';

interface DocumentContextType {
  documents: Document[];
  notifications: Notification[];
  uploadDocument: (documentData: Omit<Document, 'id' | 'uploadDate' | 'senderId' | 'senderName'>) => void;
  updateDocumentStatus: (documentId: string, userId: string, newStatus: 'pending' | 'delivered') => void;
  markNotificationAsRead: (notificationId: string) => void;
  filterDocuments: (filters: DocumentFilters) => Document[];
  getUnreadNotificationsCount: () => number;
}

const DocumentContext = createContext<DocumentContextType | undefined>(undefined);

export const DocumentProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user } = useAuth();
  const [documents, setDocuments] = useState<Document[]>([]);
  const [notifications, setNotifications] = useState<Notification[]>([]);

  // Datos simulados iniciales
  useEffect(() => {
    if (user) {
      const mockDocuments: Document[] = [
        {
          id: '1',
          folio: 'DOC-2024-001',
          title: 'Informe Trimestral Q1',
          description: 'Reporte financiero del primer trimestre',
          fileUrl: '/mock-document.pdf',
          fileName: 'informe_q1_2024.pdf',
          uploadDate: '2024-01-15',
          senderId: user.id,
          senderName: user.name,
          isUrgent: true,
          career: 'Administración',
          documentType: 'Informe',
          recipients: [
            { userId: '2', userName: 'María García', status: 'delivered' },
            { userId: '3', userName: 'Carlos López', status: 'pending' }
          ]
        },
        {
          id: '2',
          folio: 'DOC-2024-002',
          title: 'Manual de Procedimientos',
          description: 'Actualización de procedimientos operativos',
          fileUrl: '/mock-document.pdf',
          fileName: 'manual_procedimientos.pdf',
          uploadDate: '2024-01-20',
          senderId: user.id,
          senderName: user.name,
          isUrgent: false,
          career: 'Sistemas',
          documentType: 'Manual',
          recipients: [
            { userId: '2', userName: 'María García', status: 'process' }
          ]
        }
      ];

      const mockNotifications: Notification[] = [
        {
          id: '1',
          documentId: '1',
          documentTitle: 'Informe Trimestral Q1',
          senderName: 'Juan Pérez',
          type: 'document_received',
          message: 'Has recibido un nuevo documento urgente',
          timestamp: '2024-01-15T10:30:00Z',
          isRead: false,
          isUrgent: true
        }
      ];

      setDocuments(mockDocuments);
      setNotifications(mockNotifications);
    }
  }, [user]);

  const uploadDocument = (documentData: Omit<Document, 'id' | 'uploadDate' | 'senderId' | 'senderName'>) => {
    if (!user) return;

    const newDocument: Document = {
      ...documentData,
      id: Date.now().toString(),
      uploadDate: new Date().toISOString().split('T')[0],
      senderId: user.id,
      senderName: user.name
    };

    setDocuments(prev => [newDocument, ...prev]);

    // Crear notificaciones para los destinatarios
    const newNotifications: Notification[] = documentData.recipients.map(recipient => ({
      id: Date.now().toString() + recipient.userId,
      documentId: newDocument.id,
      documentTitle: documentData.title,
      senderName: user.name,
      type: 'document_received',
      message: `Has recibido un nuevo documento${documentData.isUrgent ? ' urgente' : ''}`,
      timestamp: new Date().toISOString(),
      isRead: false,
      isUrgent: documentData.isUrgent
    }));

    setNotifications(prev => [...newNotifications, ...prev]);
  };

  const updateDocumentStatus = (documentId: string, userId: string, newStatus: 'pending' | 'delivered') => {
    setDocuments(prev => prev.map(doc => {
      if (doc.id === documentId) {
        return {
          ...doc,
          recipients: doc.recipients.map(recipient => {
            if (recipient.userId === userId) {
              return {
                ...recipient,
                status: newStatus,
                viewedAt: newStatus === 'pending' ? new Date().toISOString() : recipient.viewedAt,
                confirmedAt: newStatus === 'delivered' ? new Date().toISOString() : recipient.confirmedAt
              };
            }
            return recipient;
          })
        };
      }
      return doc;
    }));
  };

  const markNotificationAsRead = (notificationId: string) => {
    setNotifications(prev => prev.map(notification => 
      notification.id === notificationId 
        ? { ...notification, isRead: true }
        : notification
    ));
  };

  const filterDocuments = (filters: DocumentFilters): Document[] => {
    return documents.filter(doc => {
      if (filters.folio && !doc.folio.toLowerCase().includes(filters.folio.toLowerCase())) {
        return false;
      }
      
      if (filters.dateFrom && doc.uploadDate < filters.dateFrom) {
        return false;
      }
      
      if (filters.dateTo && doc.uploadDate > filters.dateTo) {
        return false;
      }
      
      if (filters.career && doc.career !== filters.career) {
        return false;
      }
      
      if (filters.documentType && filters.documentType !== 'all') {
        if (filters.documentType === 'urgent' && !doc.isUrgent) {
          return false;
        }
        if (filters.documentType === 'ordinary' && doc.isUrgent) {
          return false;
        }
      }

      return true;
    });
  };

  const getUnreadNotificationsCount = (): number => {
    return notifications.filter(n => !n.isRead).length;
  };

  return (
    <DocumentContext.Provider value={{
      documents,
      notifications,
      uploadDocument,
      updateDocumentStatus,
      markNotificationAsRead,
      filterDocuments,
      getUnreadNotificationsCount
    }}>
      {children}
    </DocumentContext.Provider>
  );
};

export const useDocuments = () => {
  const context = useContext(DocumentContext);
  if (context === undefined) {
    throw new Error('useDocuments must be used within a DocumentProvider');
  }
  return context;
};
