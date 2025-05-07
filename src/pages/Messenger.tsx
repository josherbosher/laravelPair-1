import React, { useState, useEffect } from 'react';
import styled from 'styled-components';
import MessageBubble from '../components/MessageBubble';
import authService from '../services/auth';
import websocketService from '../services/websocket';

interface Message {
  id: string;
  text: string;
  sender: string;
  timestamp: Date;
  status?: 'sent' | 'delivered' | 'read' | 'error';
}

interface User {
  id: string;
  username: string;
  avatar?: string;
}

const Messenger: React.FC = () => {
  const [messages, setMessages] = useState<Message[]>([]);
  const [newMessage, setNewMessage] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [users, setUsers] = useState<User[]>([]);
  const [showAddUser, setShowAddUser] = useState(false);
  const [newUserName, setNewUserName] = useState('');

  useEffect(() => {
    const currentUser = authService.getCurrentUser();
    if (!currentUser) {
      setError('Please login to continue');
      return;
    }

    websocketService.connect(currentUser.id);
    websocketService.onMessage((message) => {
      setMessages(prev => [...prev, message]);
    });

    return () => {
      websocketService.disconnect();
    };
  }, []);

  const handleSendMessage = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    if (!newMessage.trim()) return;

    try {
      const currentUser = authService.getCurrentUser();
      if (!currentUser) throw new Error('Not authenticated');

      const message: Message = {
        id: Date.now().toString(),
        text: newMessage,
        sender: currentUser.id,
        timestamp: new Date(),
        status: 'sent'
      };

      websocketService.sendMessage(message);
      setMessages(prev => [...prev, message]);
      setNewMessage('');
    } catch (err) {
      setError('Failed to send message');
    }
  };

  const handleAddUser = async () => {
    if (!newUserName.trim()) return;
    // Here you would typically make an API call to add the user
    const newUser = { id: Date.now().toString(), username: newUserName };
    setUsers([...users, newUser]);
    setNewUserName('');
    setShowAddUser(false);
  };

  return (
    <MessengerContainer>
      <Sidebar>
        <SidebarHeader>
          <h2>Chat Users</h2>
          <AddUserButton onClick={() => setShowAddUser(true)}>
            Add User
          </AddUserButton>
        </SidebarHeader>
        <UserList>
          {users.length === 0 ? (
            <EmptyState>No users added yet</EmptyState>
          ) : (
            users.map(user => (
              <UserItem key={user.id}>
                {user.avatar && <UserAvatar src={user.avatar} alt={user.username} />}
                <span>{user.username}</span>
              </UserItem>
            ))
          )}
        </UserList>
      </Sidebar>

      <MainContent>
        <ChatContainer>
          <ChatHeader>
            <h2>Messages</h2>
          </ChatHeader>
          
          <MessageList>
            {messages.length === 0 ? (
              <EmptyState>No messages yet. Start a conversation!</EmptyState>
            ) : (
              messages.map((message) => (
                <MessageBubble
                  key={message.id}
                  message={message}
                  isOwnMessage={message.sender === authService.getCurrentUser()?.id}
                />
              ))
            )}
          </MessageList>

          <MessageForm onSubmit={handleSendMessage}>
            <MessageInputWrapper>
              <MessageInput
                value={newMessage}
                onChange={(e) => setNewMessage(e.target.value)}
                placeholder="Type a message..."
              />
              <SendButton type="submit">Send</SendButton>
            </MessageInputWrapper>
          </MessageForm>
        </ChatContainer>
      </MainContent>

      {showAddUser && (
        <Modal>
          <ModalContent>
            <h3>Add User to Chat</h3>
            <Input
              value={newUserName}
              onChange={(e) => setNewUserName(e.target.value)}
              placeholder="Enter username..."
            />
            <ModalButtons>
              <Button onClick={handleAddUser}>Add</Button>
              <Button onClick={() => setShowAddUser(false)}>Cancel</Button>
            </ModalButtons>
          </ModalContent>
        </Modal>
      )}
    </MessengerContainer>
  );
};

const MessengerContainer = styled.div`
  display: flex;
  height: calc(100vh - 40px); // Account for padding
  margin: -20px; // Offset the app padding
`;

const MainContent = styled.div`
  flex: 1;
  display: flex;
  padding: 20px;
  background: #f5f5f5;
`;

const ChatContainer = styled.div`
  display: flex;
  flex-direction: column;
  width: 100%;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
`;

const ChatHeader = styled.div`
  padding: 20px;
  border-bottom: 1px solid #eee;
  background: #fff;

  h2 {
    margin: 0;
    font-size: 1.2rem;
    color: #333;
  }
`;

const Sidebar = styled.div`
  width: 280px;
  background: white;
  border-right: 1px solid #eee;
  display: flex;
  flex-direction: column;
`;

const SidebarHeader = styled.div`
  padding: 20px;
  border-bottom: 1px solid #ddd;
  display: flex;
  justify-content: space-between;
  align-items: center;
`;

const UserList = styled.div`
  overflow-y: auto;
  flex: 1;
`;

const UserItem = styled.div`
  padding: 10px 20px;
  display: flex;
  align-items: center;
  cursor: pointer;
  
  &:hover {
    background: #eee;
  }
`;

const UserAvatar = styled.img`
  width: 32px;
  height: 32px;
  border-radius: 50%;
  margin-right: 10px;
`;

const AddUserButton = styled.button`
  padding: 5px 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
`;

const MessageList = styled.div`
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  min-height: 200px;
`;

const EmptyState = styled.div`
  text-align: center;
  color: #666;
  padding: 40px 20px;
  font-style: italic;
`;

const MessageForm = styled.form`
  display: flex;
  padding: 20px;
  border-top: 1px solid #eee;
`;

const MessageInputWrapper = styled.div`
  display: flex;
  gap: 10px;
  width: 100%;
`;

const MessageInput = styled.input`
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-right: 10px;
`;

const SendButton = styled.button`
  padding: 10px 20px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;

  &:hover {
    background: #0056b3;
  }
`;

const ErrorBanner = styled.div`
  background-color: #ff4444;
  color: white;
  padding: 10px;
  text-align: center;
  width: 100%;
`;

const Modal = styled.div`
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
`;

const ModalContent = styled.div`
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 300px;
`;

const ModalButtons = styled.div`
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
`;

const Button = styled.button`
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  background: #007bff;
  color: white;

  &:hover {
    background: #0056b3;
  }
`;

const Input = styled.input`
  width: 100%;
  padding: 8px;
  margin-top: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
`;

export default Messenger;
