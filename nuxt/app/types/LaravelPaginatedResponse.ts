export interface LaravelResponse<T> {
  data: T
}

export interface LaravelPaginatedResponse<T> {
  data: T[]
  meta: {
    total: number
    per_page: number
    current_page: number
  }
}
