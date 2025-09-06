"""
Script to generate an Entity Relationship (ER) diagram for the gaming_gear_shop
project using matplotlib. The resulting image is saved as 'er_diagram.png'
inside the project directory.

The diagram illustrates the relationships between the Admin, Customer and
Product tables. It uses simple rectangles to represent tables and arrows
to denote conceptual one-to-many relationships.
"""

import matplotlib.pyplot as plt
from matplotlib.patches import FancyBboxPatch, FancyArrowPatch


def draw_table(ax, x, y, width, height, title, fields, facecolor, edgecolor):
    """Draws a table-like rectangle with a title and list of fields."""
    # Draw outer box
    box = FancyBboxPatch((x, y), width, height,
                         boxstyle="round,pad=0.02",
                         edgecolor=edgecolor,
                         facecolor=facecolor,
                         linewidth=1.5)
    ax.add_patch(box)
    # Title
    ax.text(x + 0.02, y + height - 0.08, title, fontsize=12,
            fontweight='bold', color='black')
    # Draw fields
    for idx, field in enumerate(fields):
        ax.text(x + 0.02, y + height - 0.16 - idx * 0.08,
                field, fontsize=10, color='black')


def main():
    fig, ax = plt.subplots(figsize=(10, 6))
    ax.axis('off')

    # Table positions and sizes
    # Each table will be drawn with a fixed width and height. Since we only
    # display a few representative fields, a moderate height is sufficient.
    width = 0.32
    height = 0.35

    # Admin table at left
    draw_table(ax, 0.05, 0.4, width, height,
               'Admin',
               ['id_admin (PK)', 'username', 'password'],
               facecolor='#aedff7', edgecolor='#377ba8')

    # Customer table at top right
    draw_table(ax, 0.6, 0.6, width, height,
               'Customer',
               ['id_customer (PK)', 'username', 'password'],
               facecolor='#d6f5d6', edgecolor='#549e54')

    # Product table at bottom right
    draw_table(ax, 0.6, 0.1, width, height,
               'Product',
               ['id_product (PK)', 'name', 'price'],
               facecolor='#fff5ba', edgecolor='#c5b358')

    # Draw conceptual relationships (Admin manages many Customers and Products)
    # Arrow from Admin to Customer
    start_x = 0.05 + width
    start_y = 0.4 + height * 0.75
    end_x = 0.6
    end_y = 0.6 + height * 0.75
    arrow1 = FancyArrowPatch((start_x, start_y), (end_x, end_y),
                             arrowstyle='-|>', mutation_scale=15,
                             color='black', linewidth=1.2)
    ax.add_patch(arrow1)
    ax.text((start_x + end_x)/2, (start_y + end_y)/2 + 0.03, '1:n', fontsize=10, color='black')

    # Arrow from Admin to Product
    start_x2 = 0.05 + width
    start_y2 = 0.4 + height * 0.25
    end_x2 = 0.6
    end_y2 = 0.1 + height * 0.25
    arrow2 = FancyArrowPatch((start_x2, start_y2), (end_x2, end_y2),
                             arrowstyle='-|>', mutation_scale=15,
                             color='black', linewidth=1.2)
    ax.add_patch(arrow2)
    ax.text((start_x2 + end_x2)/2, (start_y2 + end_y2)/2 - 0.05, '1:n', fontsize=10, color='black')

    # Save figure
    output_path = 'er_diagram.png'
    plt.savefig(output_path, dpi=300, bbox_inches='tight')
    print(f'ER diagram saved as {output_path}')


if __name__ == '__main__':
    main()