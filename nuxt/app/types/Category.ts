export type Category = {
  id: string | number;
  title: string;
  slug?: string;
  description?: string | null;
  parent_id?: string | number | null;
  parent_category?: string | null; // Змінили з parent_title на parent_category
}
