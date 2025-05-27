
export interface User {
  id: string;
  name: string;
  email: string;
  role: 'admin' | 'user';
  career?: string;
  avatar?: string;
  createdAt?: string;
  status: 'active' | 'inactive';
}

export interface Document {
  id: string;
  folio: string;
  title: string;
  description: string;
  fileUrl: string;
  fileName: string;
  uploadDate: string;
  senderId: string;
  senderName: string;
  isUrgent: boolean;
  career: string;
  documentType: string;
  recipients: DocumentRecipient[];
}

export interface DocumentRecipient {
  userId: string;
  userName: string;
  status: 'process' | 'pending' | 'delivered';
  viewedAt?: string;
  confirmedAt?: string;
}

export interface Notification {
  id: string;
  documentId: string;
  documentTitle: string;
  senderName: string;
  type: 'document_received' | 'status_change' | 'user_created' | 'user_updated';
  message: string;
  timestamp: string;
  isRead: boolean;
  isUrgent: boolean;
}

export interface DocumentFilters {
  folio?: string;
  dateFrom?: string;
  dateTo?: string;
  career?: string;
  documentType?: 'urgent' | 'ordinary' | 'all';
  status?: 'process' | 'pending' | 'delivered' | 'all';
}

export interface CreateUserData {
  name: string;
  email: string;
  password: string;
  role: 'admin' | 'user';
  career?: string;
}

export interface UpdateUserData {
  name?: string;
  email?: string;
  role?: 'admin' | 'user';
  career?: string;
  status?: 'active' | 'inactive';
}
