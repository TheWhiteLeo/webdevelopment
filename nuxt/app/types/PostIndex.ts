export type PostIndex = {
  "id": string,
  "title": string,
  "slug": string,
  "is_published": boolean,
  "published_at": string | null,
  "category_title": string,
  "author_name": string
}

export type PostDetail = PostIndex & {
  category_id: string,
  author_id: string,
  content_raw: string,
  content_html: string,
}
