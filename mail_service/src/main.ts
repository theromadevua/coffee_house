import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { MicroserviceOptions, Transport } from '@nestjs/microservices';

async function bootstrap() {
  const app = await NestFactory.createMicroservice<MicroserviceOptions>(AppModule, {
    transport: Transport.REDIS,
    options: {
      host: process.env.MICROSERVICE_HOST,
      port: Number(process.env.MICROSERVICE_PORT),
      db: 0
    },
  });

  await app.listen();
  console.log('Order microservice is listening...');
}
bootstrap();
