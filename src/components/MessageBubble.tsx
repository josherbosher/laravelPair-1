import React from 'react';
import styled from 'styled-components';

interface MessageBubbleProps {
  message: {
    id: string;
    text: string;
    sender: string;
    timestamp: Date;
    status?: 'sent' | 'delivered' | 'read' | 'error';
  };
  isOwnMessage: boolean;
}

const MessageBubble: React.FC<MessageBubbleProps> = ({ message, isOwnMessage }) => {
  return (
    <BubbleContainer isOwnMessage={isOwnMessage}>
      <Bubble isOwnMessage={isOwnMessage}>
        <MessageText>{message.text}</MessageText>
        <MessageInfo>
          <TimeStamp>
            {message.timestamp.toLocaleTimeString([], { 
              hour: '2-digit', 
              minute: '2-digit' 
            })}
          </TimeStamp>
          {isOwnMessage && <Status status={message.status}>
            {message.status === 'error' && '⚠️'}
            {message.status === 'sent' && '✓'}
            {message.status === 'delivered' && '✓✓'}
            {message.status === 'read' && '✓✓'}
          </Status>}
        </MessageInfo>
      </Bubble>
    </BubbleContainer>
  );
};

const BubbleContainer = styled.div<{ isOwnMessage: boolean }>`
  display: flex;
  justify-content: ${props => props.isOwnMessage ? 'flex-end' : 'flex-start'};
  margin: 8px 0;
`;

const Bubble = styled.div<{ isOwnMessage: boolean }>`
  background: ${props => props.isOwnMessage ? '#007bff' : '#e9ecef'};
  color: ${props => props.isOwnMessage ? 'white' : 'black'};
  padding: 8px 16px;
  border-radius: 16px;
  max-width: 70%;
  word-wrap: break-word;
`;

const MessageText = styled.p`
  margin: 0;
`;

const MessageInfo = styled.div`
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.75rem;
`;

const TimeStamp = styled.span`
  opacity: 0.7;
`;

const Status = styled.span<{ status?: string }>`
  color: ${props => props.status === 'error' ? 'red' : 'inherit'};
`;

export default MessageBubble;
