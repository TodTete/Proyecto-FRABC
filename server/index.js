import express from "express";
import cors from 'cors';
import dotenv from "dotenv";
import Indexrouter from './routes/core.routes.js';

//import {dirname, join} from 'path';
//import { fileURLToPath} from 'url';
dotenv.config();
const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

app.use(Indexrouter);
//app.use(router);

//const _dirname = dirname(fileURLToPath(import.meta.url));
//app.use(express.static(join(_dirname, '../client/dist')));

app.listen(PORT, () => {
    console.log(`Servidor escuchando en el puerto ${PORT}`);
});
