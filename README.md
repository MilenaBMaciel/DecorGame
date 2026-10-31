# DecorGame

A house decorating game built with Symfony, inspired by the browser games of the 2000s and early 2010s.

You walk through the rooms of a house (living room, kitchen, bedroom, bathroom) and pick the furniture that goes in each one. An administrator manages the rooms and the furniture available in the game.

> University project, built by following the Symfony course in the [unsiteavous/Symfony7](https://github.com/unsiteavous/Symfony7) repository.

## Artwork

All the rooms and furniture in this game were **drawn by hand in pixel art by the author, Milena Bueno Maciel**. The original files are in [`Game/fixtures/images/`](Game/fixtures/images).

## Inspiration

The look and the gameplay come from the "dress up and decorate" games that filled kids' game sites in the Flash era, such as the **Polly Pocket**, **Sue** and **Barbie** games:

- a fixed, hand-drawn scene filling the screen;
- a few big buttons that are easy to click;
- no score and no game over: just pick, look at the result and change your mind;
- pixel fonts and bright colors (`FatPix` and `PixelLove`, in `Game/assets/fonts`).

| Avatar Star Sue, "Girl's Beauty Room" | Polly Pocket decorating game |
|---|---|
| <img src="Game/fixtures/images/readme/inspiration1.png" width="400" alt="Title screen of the Sue game, Girl's Beauty Room"> | <img src="Game/fixtures/images/readme/inspiration2.png" width="400" alt="Room decorating screen of a Polly Pocket game"> |

*Screenshots of the games that inspired this project, shown for reference only. All rights belong to their respective owners.*

## Screenshots

| Home screen | Game menu |
|---|---|
| <img src="Game/fixtures/images/readme/firstMenu.png" width="400" alt="DecorGame home screen with Sign in and Sign up buttons"> | <img src="Game/fixtures/images/readme/secondMenu.png" width="400" alt="DecorGame menu with Start, Edit Game and Logout buttons"> |

| A room (bedroom) | Admin: furniture list |
|---|---|
| <img src="Game/fixtures/images/readme/room.png" width="400" alt="Bedroom with three pieces of furniture in its slots"> | <img src="Game/fixtures/images/readme/editFurniture.png" width="400" alt="Admin page listing the furniture with Edit buttons"> |

## How the game works

1. **Rooms.** Each room has a background image and **3 slots** (positions 1, 2 and 3, from left to right).
2. **Furniture.** Each slot holds one piece of furniture or stays empty. Each piece of furniture is an image with a name.
3. **Individual decoration.** Rooms and furniture are global (created by the admin), but **each player has their own decoration**. A player's slots in a room are created empty the first time they open that room.
4. **Navigation.** Inside a room, previous/next buttons move through the house, in the order the rooms were created.
5. **Choosing furniture.** Clicking a slot opens the furniture panel. Your choice shows up in the scene right away (preview only) and is saved only when you click **Save**. The "×" option empties the slot.

## Roles

| Role | What they can do |
|---|---|
| Visitor | See the home screen, sign up and sign in |
| Player (`ROLE_USER`) | Play and decorate their own rooms |
| Administrator (`ROLE_ADMIN`) | Everything a player can do, plus the **Edit Game** menu |

In the **Edit Game** menu the administrator can:

- create, edit and delete **rooms** (name and image);
- create, edit and delete **furniture** (name and image);
- email themselves a **report** of rooms and furniture, attached as JSON.

Uploaded images must be PNG, JPG, GIF or WEBP, up to 2 MB. When a piece of furniture is deleted, the slots that used it become empty. When a room is deleted, its slots are deleted too.

## Getting started

### Requirements

- PHP 8.4 or higher, with the usual Symfony extensions and `pdo_mysql`
- [Composer](https://getcomposer.org/)
- MySQL 8

> The `compose.yaml` that ships with the project starts a PostgreSQL database, but the migrations were written for MySQL. Use MySQL.

### Installation

Run the commands inside the `Game/` folder.

```bash
cd Game
composer install
```

1. Create a `.env.local` file (it is not committed to git) with the database connection:

   ```dotenv
   DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/game_db?serverVersion=8.0"
   ```

2. Create the database and tables:

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

3. Load the sample data (4 rooms, 22 pieces of furniture and 2 users). This wipes the current database content:

   ```bash
   php bin/console doctrine:fixtures:load
   ```

   The sample images live in `fixtures/images/` and are copied to `public/uploads/`.

4. Start the server:

   ```bash
   symfony serve -d
   # or, without the Symfony CLI:
   php -S localhost:8000 -t public
   ```

5. Open the address shown in the terminal.

### Sample users

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@decorgame.com` | `admin123` |
| Player | `user@decorgame.com` | `user123` |

You can also create a new account with **Sign up**. New accounts are always regular players.

### Report email (optional)

By default (`MAILER_DSN=null://null` in `.env`) no email is actually sent. To try the **Send Report** button, set `MAILER_DSN` in `.env.local`. The simplest way is [Mailpit](https://mailpit.axllent.org/), a test mailbox:

```bash
docker run -d --name mailpit -p 1025:1025 -p 8025:8025 axllent/mailpit
```

```dotenv
MAILER_DSN=smtp://localhost:1025
```

Emails show up at http://localhost:8025.

## API (administrators only)

| Route | Returns |
|---|---|
| `GET /api/rooms` | rooms as JSON |
| `GET /api/furniture` | furniture as JSON |

They use the browser session, so you must be logged in as an administrator.

## Project structure

```
Game/
├── src/
│   ├── Controller/      game screens, admin screens, login/registration
│   │   └── API/         JSON routes
│   ├── Entity/          User, Room, Furniture, Slot
│   ├── Form/            forms (room, furniture, slot, registration)
│   ├── Service/         SlotManager (per-player slots), FileUploader (images)
│   ├── Repository/      database queries
│   ├── DataFixtures/    sample data
│   └── Security/        form login authentication
├── templates/           Twig views
├── assets/              CSS, fonts, theme images and JS (Stimulus + Turbo)
├── fixtures/images/     sample images (the pixel art)
└── public/uploads/      images uploaded by the admin (not committed)
```

## Technologies

Symfony 8.1, PHP 8.4, Doctrine ORM and Migrations, Twig, Security Bundle, Symfony Form and Validator, Serializer, Mailer, MySQL.
