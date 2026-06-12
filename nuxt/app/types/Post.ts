import type { Category } from '~/types/Category'
import type { User } from '~/types/User'

export type Post = {
  id: string
  title: string
  slug: string
  is_published: number
  published_at: string | null
  user_id: string
  category_id: string
  content_raw: string
  content_html: string
  category: Category
  user: User
}
