import {getSecret} from './secret';
import client from './client';

export const SecretClient = getSecret;

export const BaseClient = client;
