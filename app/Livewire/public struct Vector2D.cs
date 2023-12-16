public struct Vector2D
{
    public int X;
    public int Y;

    public Vector2D(int x, int y)
    {
        X = x;
        Y = y;
    }
}

 public int SolveMaxCount1R(Vector2D Parent, Vector2D Item)
    {
        // make a list to hold both the item size and its rotation
        List<Vector2D> itemSizes = new List<Vector2D>();
        itemSizes.Add(Item);
        if (Item.X != Item.Y)
        {
            itemSizes.Add(new Vector2D(Item.Y, Item.X));
        }

        int solution = SolveGeneralMaxCount(Parent, itemSizes.ToArray());
        return solution;
    }

     SolverClass solver = new SolverClass();
        int count = solver.SolveMaxCount1R(new Vector2D(2500, 3800), new Vector2D(425, 550));
        //(all units are in tenths of a millimeter to make everything integers)

        public int SolveGeneralMaxCount(Vector2D Parent, Vector2D[] ItemSizes)
    {
        // determine the maximum x and y scaling factors using GCDs (Greastest
        //  Common Divisor)
        List<int> xValues = new List<int>();
        List<int> yValues = new List<int>();
        foreach (Vector2D size in ItemSizes)
        {
            xValues.Add(size.X);
            yValues.Add(size.Y);
        }
        xValues.Add(Parent.X);
        yValues.Add(Parent.Y);

        int xScale = NaturalNumbers.GCD(xValues);
        int yScale = NaturalNumbers.GCD(yValues);

        // rescale our parameters
        Vector2D parent = new Vector2D(Parent.X / xScale, Parent.Y / yScale);
        var baseShapes = new Dictionary<Vector2D, Vector2D>();
        foreach (var size in ItemSizes)
        {
            var reducedSize = new Vector2D(size.X / xScale, size.Y / yScale);
            baseShapes.Add(reducedSize, reducedSize);
        }

        //determine the minimum values that an allowed item shape can fit into
        _xMin = int.MaxValue;
        _yMin = int.MaxValue;
        foreach (var size in baseShapes.Keys)
        {
            if (size.X < _xMin) _xMin = size.X;
            if (size.Y < _yMin) _yMin = size.Y;
        }

        // create the memoization cache for shapes
        Dictionary<Vector2D, SizeCount> shapesCache = new Dictionary<Vector2D, SizeCount>();

        // find the solution pattern with the most finished items
        int best = solveGMC(shapesCache, baseShapes, parent);

        return best;
    }

    private int _xMin;
    private int _yMin;


     private int solveGMC(
        Dictionary<Vector2D, SizeCount> shapeCache,
        Dictionary<Vector2D, Vector2D> baseShapes,
        Vector2D sheet )
    {
        // have we already solved this size?
        if (shapeCache.ContainsKey(sheet)) return shapeCache[sheet].ItemCount;

        SizeCount item = new SizeCount(sheet, 0);

        if ((sheet.X < _xMin) || (sheet.Y < _yMin))
        {
            // if it's too small in either dimension then this is a scrap piece
            item.ItemCount = 0;
        }

        else  // try every way of cutting this sheet (guillotine cuts only)
        {
            int child0;
            int child1;

            // try every size of horizontal guillotine cut
            for (int c = sheet.X / 2; c > 0; c--)
            {
                child0 = solveGMC(shapeCache, baseShapes, new Vector2D(c, sheet.Y));
                child1 = solveGMC(shapeCache, baseShapes, new Vector2D(sheet.X - c, sheet.Y));

                if (child0 + child1 > item.ItemCount)
                {
                    item.ItemCount = child0 + child1;
                }
            }

            // try every size of vertical guillotine cut
            for (int c = sheet.Y / 2; c > 0; c--)
            {
                child0 = solveGMC(shapeCache, baseShapes, new Vector2D(sheet.X, c));
                child1 = solveGMC(shapeCache, baseShapes, new Vector2D(sheet.X, sheet.Y - c));

                if (child0 + child1 > item.ItemCount)
                {
                    item.ItemCount = child0 + child1;
                }
            }

            // if no children returned finished items, then the sheet is
            //  either scrap or a finished item itself
            if (item.ItemCount == 0)
            {
                if (baseShapes.ContainsKey(item.Size))
                {
                    item.ItemCount = 1;
                }
                else
                {
                    item.ItemCount = 0;
                }
            }
        }

        // add the item to the cache before we return it
        shapeCache.Add(item.Size, item);

        return item.ItemCount;
    }


    static class NaturalNumbers
{
    /// <summary>
    /// Returns the Greatest Common Divisor of two natural numbers.
    ///   Returns Zero if either number is Zero,
    ///   Returns One if either number is One and both numbers are >Zero
    /// </summary>
    public static int GCD(int a, int b)
    {
        if ((a == 0) || (b == 0)) return 0;
        if (a >= b)
            return gcd_(a, b);
        else
            return gcd_(b, a);
    }

    /// <summary>
    /// Returns the Greatest Common Divisor of a list of natural numbers.
    ///  (Note: will run fastest if the list is in ascending order)
    /// </summary>
    public static int GCD(IEnumerable<int> numbers)
    {
        // parameter checks
        if (numbers == null || numbers.Count() == 0) return 0;

        int first = numbers.First();
        if (first <= 1) return 0;

        int g = (int)first;
        if (g <= 1) return g;

        int i = 0;

        foreach (int n in numbers)
        {
            if (i == 0)
                g = n;
            else
                g = GCD(n, g);

            if (g <= 1) return g;
            i++;
        }
        return g;
    }

    // Euclidian method with Euclidian Division,
    //  From: https://en.wikipedia.org/wiki/Euclidean_algorithm
    private static int gcd_(int a, int b)
    {
        while (b != 0)
        {
            int t = b;
            b = (a % b);
            a = t;
        }
        return a;
    }

}

public class SizeCount
{
    public Vector2D Size;
    public int ItemCount;

    public SizeCount(Vector2D itemSize, int itemCount)
    {
        Size = itemSize;
        ItemCount = itemCount;
    }
}