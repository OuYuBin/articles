import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;

public class SmartMinimize {

public static void main(String[] args) {
	Display display = new Display();
	Shell shell = new Shell(display);
	shell.setText("Main");
	Menu menu = new Menu(shell, SWT.BAR);
	shell.setMenuBar(menu);
	
	shell.open();
	
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}

