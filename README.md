# API-ToDo
### Necessário:
    - Docker e docker-compose.
    - Composer.
    - Make.
### Execução:
    - make run
    - porta: 8000

#### Rotas:
   ```/api/auth/register [POST] => Registro de usuário.```
  - Payload: <br/>
    ```json
        {
            "name": "string",
            "email": "string",
            "password": "string"
        }
    ```

  ```/api/auth/login [POST] => Login do usuário.```
  - Payload: <br/>
    ```json
        {
            "email": "string",
            "password": "string"
        }
    ```

  - <b>Auth:</b>
      - <b>Users:</b> <br/>
          ```/api/profile [GET] => Visualizar os dados do usuário logado.```  <br/>
          ```/api/profile [PUT] => Atualizar os dados do usuário.``` <br/>
          - Payload:
              ```json 
                {
                    "name": "string",
                    "email": "string",
                    "password": "string"
                }
              ```
          ```/api/profile [DELETE] => Excluir perfil.``` <br/>

      - <b>ToDos:</b> <br/>
          ```/api/todos [POST] => Criar um todo.``` <br/>
        - Payload:
            ```json 
                {
                    "title": "string",
                    "description": "string",
                    "done": "boolean",
                    "done_at": "string",
                    "user_id": "string && uuid"
                }
            ```
        ```/api/todos [GET] => Listar todos os todos.``` <br/>
        ```/api/todos/{id} [GET] => Visualizar um todo.``` <br/>
        ```/api/todos/{id} [PUT] => Atualizar um todo.``` <br/>
         - Payload:
              ```json 
                { 
                    "title": "string",
                    "description": "string",
                    "done": "boolean",
                    "done_at": "string"
                }
              ```
        ```/api/todos/{id} [DELETE] => Deletar um todo.``` <br/>
        ```/api/todos/{id}/status/{status} [POST] => Alterar status de um todo. ({status}: 'done' || 'undone')```
