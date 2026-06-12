import type { Post } from "~/types/Post";

export interface LaravelPaginatedResponse {
  data: Post[]
  total: number
  per_page: number
  current_page: number
}
