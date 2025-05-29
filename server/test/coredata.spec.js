import { test, expect } from '@playwright/test';

test('Ruta /coredata devuelve mensaje y resultado esperado', async ({ request }) => {
  const response = await request.get('http://localhost:3000/coredata');

  expect(response.status()).toBe(200);

  const data = await response.json();
  expect(data).toHaveProperty('message', '¡Conexión exitosa!');
  expect(data.result).toHaveProperty('result', 2);
});
