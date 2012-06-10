CREATE TABLE "files" (
  "id" integer NOT NULL PRIMARY KEY,
  "hash" text NOT NULL,
  "content" text NOT NULL,
  "views" integer NOT NULL,
  "ip" text NOT NULL,
  "creation" text NOT NULL
);
