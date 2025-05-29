import { test, expect } from '@playwright/test';

test('Ruta /ping responde correctamente', async ({ request }) => {
  const response = await request.get('http://localhost:3000/ping');
  
  expect(response.status()).toBe(200);
  
  const data = await response.json();
  expect(Array.isArray(data)).toBeTruthy();
  expect(data[0]).toHaveProperty('result', 2);
});
