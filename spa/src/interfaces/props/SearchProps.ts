export interface SearchProps {
  searchTerm: string;
  onSearchTermChange: (value: string) => void;
  options: string[];
}
