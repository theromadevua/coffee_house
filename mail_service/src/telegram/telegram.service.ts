import { Injectable, Logger } from '@nestjs/common';
import * as TelegramBot from 'node-telegram-bot-api';

@Injectable()
export class TelegramService {
  private bot: TelegramBot;
  private readonly logger = new Logger(TelegramService.name);

  constructor() {
    const token = process.env.TELEGRAM_BOT_TOKEN;
    this.bot = new TelegramBot(token, { polling: true });
    this.bot.on('message', (msg) => {
        this.logger.log(`Новое сообщение от ${msg.from?.username || 'неизвестно'} (${msg.chat.id}): ${msg.text}`);
      });
      
  }

  async sendMessage(message: string, chatId?: string): Promise<void> {
    const targetChatId = chatId || process.env.TELEGRAM_ADMIN_CHAT_ID;
    if (!targetChatId) {
      this.logger.warn('Chat ID не указан');
      return;
    }

    try {
      await this.bot.sendMessage(targetChatId, message);
      this.logger.log(`Отправлено сообщение: ${message}`);
    } catch (error) {
      this.logger.error(`Ошибка при отправке сообщения: ${error.message}`);
    }
  }
}
